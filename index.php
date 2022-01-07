<?php session_start();



require 'classes/class.alterar.php';
require 'classes/class.botoes.php';
require 'classes/class.conexao.php';
require 'classes/class.configuracoes.php';
require 'classes/class.excluir.php';
require 'classes/class.faz.html.php'; // Não instanciar
require 'classes/class.formularios.php';
require 'classes/class.front.end.php';
require 'classes/class.funcoes.php'; // Não instanciar
require 'classes/class.inserir.php';
require 'classes/class.listar.php';
require 'classes/class.post.php';
require 'classes/class.procurar.php';
require 'classes/class.servicos.php'; // Não instanciar
require 'classes/class.validacoes.php';

$objAlterar     = new ClassAlterar();
$objBotoes      = new ClassBotoes();
$objConexao     = new ClassConexao();
$objConfig      = new ClassConfiguracoes();
$objExcluir     = new ClassExcluir();
$objFormularios = new ClassFormularios();
$objFrontEnd    = new ClassFrontEnd();
$objInserir     = new ClassInserir();
$objListar      = new ClassListar();
$objPost        = new ClassPost();
$objProcurar    = new ClassProcurar();
$objValidacoes  = new ClassValidacoes();

// Configurações inciais
$objPost->ObterPost(); 
$objConfig->CarregarTodosModelosHtml();
$objConfig->CarregarConfiguracoes();
$objConfig->PrepararRegiao();
$objConfig->GerarLinksExtrasPrincipais();
$objConfig->CarregarMensagensPrincipais();
$objConfig->CarregarEntidade($_POST['Tabela']);
$objConfig->CarregarMensagensDaTabela($_POST['Tabela']);
$objConexao->CarregarBancoDados($_SESSION['Configuracoes']['BancoDados']);

// Abre a conexao
if (!$objConexao->isConectado()) $objConexao->ConAbrir();

// // Carrega as entidades e prepara os Menus (Categorias e Subcategorias)
// foreach (array('Categorias','Subcategorias') as $nomeTabela){
//     $objConfig->CarregarEntidade($nomeTabela);
//     $registros = ClassServicos::ListarTodosDaTabela($objConexao, $_SESSION['Entidades'][$nomeTabela], true);
//     $objFrontEnd->setRegistros($nomeTabela, $registros);
//     if ($nomeTabela === 'Categorias'){
//         if (isset($_SESSION['Entidades'][$_POST['Tabela']]['Procurar']['Html']['CategoriaId']))
//             $objProcurar->setRegistros($nomeTabela, $registros);

//         if (isset($_SESSION['Entidades'][$_POST['Tabela']]['Formularios']['Html']['CategoriaId']))
//             $objFormularios->setRegistros($nomeTabela, $registros);
//     }
// }

// Prepara o Procurar 
if (isset($_SESSION['Entidades'][$_POST['Tabela']]['Procurar'])){ 
    require 'index.procurar.php';
}

// Ações principais
if ($_POST['Acao'] == 1) { // Salvar
    require 'index.validacoes.php';
    if (!$objValidacoes->getErroEncontrado()) {
    
        if ($_POST['Setor'] === 'Alterar') {
            require 'index.alterar.php';
        }
        else
        if ($_POST['Setor'] === "Inserir") {
            require 'index.inserir.php';
        } 
    }
} else
if ($_POST['Acao'] == 3) { // Excluir
    require 'index.excluir.php';
}


if ($_POST['Setor'] === 'Inserir' || $_POST['Setor'] === 'Alterar') { 
    require 'index.formularios.php';
}
else
if ($_POST['Setor'] === 'Listar' && $_POST['Setor'] !== 'Home') {
    require 'index.listar.php';
} 

// Fecha Conexao
if ($objConexao->isConectado()) $objConexao->ConFechar();


// Setor final
require 'index.front-end.php';
