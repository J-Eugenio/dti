<?php
include_once '../../config/config.php';
include_once '../../config/Conexao.class.php';
include_once '../../model/Usuario.class.php';
include_once '../../model/Recurso.class.php';

$usu_inst = new Usuario;
$equip_inst = new Recurso;

$usu_inst->redirecNaoAdm();

$usu_logado = $usu_inst->getUsuarioLogado();

$pagindice = isset($_GET['pagindice']) ? $_GET['pagindice'] : 1;

$lista_equips = $equip_inst->buscarRecursos(empty($_GET['termo']) ? '' : $_GET['termo'], 10, $pagindice);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
  <meta name="description" content=""/>
  <meta name="author" content=""/>
  <!--[if IE]>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <![endif]-->
  <title>Sistema de Reservas e Solicitações - DTI</title>
  <!-- BOOTSTRAP CORE STYLE  -->
  <link href="<?php echo URL_BASE ?>/css/bootstrap.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo URL_BASE ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

  <script src="<?php echo URL_BASE ?>/js/jquery-1.12.3.js"></script>

  <link href="<?php echo URL_BASE ?>/css/font-awesome.css" rel="stylesheet"/>

  <link href="<?php echo URL_BASE ?>/css/style.css" rel="stylesheet"/>

  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->

  <link rel="stylesheet" href="<?php echo URL_BASE ?>/css/jquery-ui.css">
  <link rel="icon" type="imagem/png" href="<?php echo URL_BASE; ?>/assets/img/dti.png" />
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
</head>
<body>
  <?php include_once '../menu-topo.php' ?>
  <div class="content-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h4 class="page-head-line">Buscar equipamentos</h4>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-primary table-ajustes">
            <div class="panel-heading">
              <h3 class="panel-title">Tabela de equipamentos - Exibindo página
              <strong><?php echo $lista_equips['pagina_atual'] ?></strong>
               de
              <strong><?php echo $lista_equips['limites_pagina'] ?></strong>
              - <strong><?php echo $lista_equips['qtd_resultados'] ?></strong> Resultados
            </h3>
            </div>
            <div class="panel-body">
              <div class="col-md-12 form-group">
                <form action="" method="get" id="form-busca">
                  <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                    <div class="input-group-addon"><span class="fa fa-search"></span></div>
                    <input type="hidden" name="pagindice" value="1">
                    <input type="text" class="form-control" name="termo"
                    placeholder="Informe uma palavra chave para a busca..."
                    value="<?php echo isset($_GET['termo']) ? $_GET['termo'] : '' ?>">
                  </div>
                </form>
              </div>
              <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="50%">
                  <thead>
                    <tr>
                      <th>Nome do equipamento</th>
                      <th>Descrição</th>
					  <th>Campus</th>
                      <th class="text-center">Ações</th>
                    </tr>
                  </thead>
                  <tbody style="overflow: auto; height: 300px">
                    <?php foreach ($lista_equips['resultados'] as $equip_row): ?>
                      <tr>
                        <td><?php echo $equip_row['nome']; ?></td>
                        <td><?php echo $equip_row['descricao']; ?></td>
						<td><?php echo $equip_row['campus']; ?></td>
                        <td class="text-center">
                          <a href="<?php echo $equip_row['id']; ?>"
                            class="btn btn-sm btn-danger excluir-equip">
                            <span class="fa fa-trash"></span> Excluir</a>
                          <a href="<?php echo URL_BASE.'/view/administrador/formulario-equipamento.php?idrecurso='.
                          $equip_row['id']; ?>"
                            class="btn btn-sm btn-primary">
                            <span class="fa fa-cogs"></span> Atualizar</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="panel-footer">
              <ul class="pagination" style="margin: 0;">
                <li class="paginate_button previous <?php echo $lista_equips['primeira_pag'] ? 'disabled' : '' ?>"
                  id="example_previous">
                  <a href="" aria-controls="example" data-dt-idx="0" tabindex="0">&lt;</a>
                </li>
                <li class="paginate_button active">
                  <a href="#" aria-controls="example" data-dt-idx="1" tabindex="0"><?php echo $pagindice ?></a>
                </li>
                <li class="paginate_button next <?php echo $lista_equips['ultima_pag'] ? 'disabled' : '' ?>"
                   id="example_next">
                  <a href="" aria-controls="example" data-dt-idx="2" tabindex="0">&gt;</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-conf-exc-equip" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmar exclusão do equipamento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>
          Tem certeza que deseja excluir este equipamento?<br/> Se fizer isso as reservas
          direcionadas a ele também serão excluídas.
        </p>
      </div>
      <div class="modal-footer">
        <button id="btn-confirma-exclusao-equip" class="btn btn-success">
          <span class="fa fa-check"></span> Sim</button>
        <button class="btn btn-danger" data-dismiss="modal">
          <span class="fa fa-close"></span> Não</button>
      </div>
    </div>
  </div>
</div>

<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        &copy; 2018 Núcleo de Extensão em Análise e Desenvolvimento de Sistemas | <a href="http://www.nexasfvs.com.br/" target="_blank">NEXAS</a>
      </div>
    </div>
  </div>
</footer>
<script src="<?php echo URL_BASE ?>/js/bootstrap.js"></script>
<script type="text/javascript">
var formBusca = $('#form-busca');
var idSelecEquip;

$('.excluir-equip').click(function(evt){
  idSelecEquip = $(this).attr('href');
  $('#modal-conf-exc-equip').modal('show');
  evt.preventDefault();
});

$('#btn-confirma-exclusao-equip').click(function(){
  window.location.href = '<?php echo URL_BASE ?>'+
  '/control/equipamento-controle.php?acao=excluir-equipamento&id='+idSelecEquip;
});

$('#example_previous a').click(function(evt){
  formBusca.find('input[name=pagindice]').val(<?php echo $lista_equips['pagina_atual']-1 ?>);
  formBusca.submit();
  evt.preventDefault();
});

$('#example_next a').click(function(evt){
  formBusca.find('input[name=pagindice]').val(<?php echo $lista_equips['pagina_atual']+1 ?>);
  formBusca.submit();
  evt.preventDefault();
});
</script>
</body>
</html>
