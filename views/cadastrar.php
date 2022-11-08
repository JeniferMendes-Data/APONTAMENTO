<?php
if (session_status() == 1){
    session_start();
}
include $_SERVER["DOCUMENT_ROOT"].'/functions/include.php';
?>
<!doctype html>
<html lang="pt-br">
	<head>
    <?php echo include_head('APONTAMENTO | CADASTRAR');?>
	</head>
	<body>
    <?php
           //verifica se a sessão está ativa
           if (!isset($_SESSION["nomeUsuario"])) {
                header("Location: ../index.php");
                die();
            } 
            
            echo include_menu("Cadastrar","Cadastros e Configurações");
       
    	?>
        <body>
        <div class="container-fluid" id ="posiciona2" >
    <div class="row flex-nowrap">
        <div class="col-auto col-md-2 col-xl-1 px-sm-1 px-0 bg">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <a class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none d-sm-inline">Menu</span>
                </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <li class="nav-item">
                        <a href="home.php" class="nav-link align-middle px-0">
                            <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline text-white">Início</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="#submenu2" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
                            <i class="fs-4 bi-bootstrap"></i> <span class="ms-1 d-none d-sm-inline text-white" >Cadastrar</span></a>
                        <ul class="collapse nav flex-column ms-1" id="submenu2" data-bs-parent="#menu">
                            <li class="w-100">
                                <a href="#" class="nav-link px-0" onclick = "js_sumir('c1')" data-nome="c1" id="d1"> <span class="d-none d-sm-inline text-white">Parte\Peça</span></a>
                            </li>
                            <li>
                                <a href="#" class="nav-link px-0" onclick = "js_sumir('c2')" data-nome="c2" id="d2"> <span class="d-none d-sm-inline text-white">Os Generica</span></a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#submenu3" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                            <i class="fs-4 bi-grid"></i> <span class="ms-1 d-none d-sm-inline text-white">Configuração</span> </a>
                            <ul class="collapse nav flex-column ms-1" id="submenu3" data-bs-parent="#menu">
                            <li class="w-100">
                                <a href="#" class="nav-link px-0" onclick = "js_sumir('c3')" data-nome="c3" id="d3"> <span class="d-none d-sm-inline text-white">Abrir Periodo</span></a>
                            </li>
                            <li>
                                <a href="#" class="nav-link px-0" onclick = "js_sumir('c4')" data-nome="c4" id="d4"> <span class="d-none d-sm-inline text-white">Permissões</span></a>
                            </li>
                            
                        </ul>
                    </li>
                    
                </ul>
                <hr>
                
            </div>
        </div>
        
            
        </div>
    </div>
</div>
            <!-- <div class="container text-center my-3 container-md col-md-6 configdiv mt-5">
                <form>
                    <div class="mb-3 mt-5 row" id="c1"> 
                    <h4>Cadastro Parte Peça e Atividade </h4>
                        <label for="exampleInputPassword1" class="form-label  col-md-1">Coligada</label>
                        <select class="form-select-sm mb-3 col-md-3" aria-label=".form-select-lg" >
                        <label for="exampleInputPassword1" class="form-label  col-md-1">Filial</label>
                        <select class="form-select-sm mb-3 col-md-3" aria-label=".form-select-lg" >
                        <label for="exampleInputPassword1" class="form-label  col-md-1">Filial</label> 
                        <select class="form-select-sm mb-3 col-md-3" aria-label=".form-select-lg" >
                        <select class="form-select-sm mb-3 col-md-3" aria-label=".form-select-sm" >
                        <label for="exampleInputPassword1" class="form-label col-md-1">Seção</label>  
                        <select class="form-select-sm mb-3 col-md-3" aria-label=".form-select-lg" ></select>  
                        
                   
                        <div class="mb-5">
                            <label for="exampleInputPassword1" class="form-label mt-1"> Parte </label>
                            <input type="password" class="form-control mt-1" id="exampleInputPassword1">
                            <label for="exampleInputPassword1" class="form-label mt-3">Atividade</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>
                            <div class="col-md-3 mt-4">
                                <button type="submit" class="btn btn-success">Criar</button>
                                <button type="submit" class="btn btn-primary">Inativar</button>
                                <button type="submit" class="btn btn-danger">Apagar</button>
                            </div>
                    </div>
                    </form>              
            </div>
        <div class="text-center my-3 container-md col-md-6 configdiv mt-5">
         <form>
            <div class="mb-3" id="c2">
            <h4> Cadastro Os Generica </h4>
                <label for="exampleInputEmail1" class="form-label">Numero da Os</label>
                <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                <button type="submit" class="btn btn-primary mt-4">salvar</button>
         </div>
            </form>
        </div>
            <div class="text-center my-3 container-md col-md-6 configdiv mt-5" id="c3">
            <h4> Configurar Periodo </h4>
            <label for="exampleInputPassword1" class="form-label mt-5">Data retroativo</label>
            <input type="date" class="form-control-sm mt-5" id="exampleInputPassword1">
            <div class= 'mt-2'></div>
            <button type="submit" class="btn btn-primary mt-5">salvar</button>
        
        </div>
        <div class="text-center my-3 container-md col-md-6 configdiv mt-5" id="c4">
            <h4> Configurar Permissões </h4>
            <label for="exampleInputEmail1" class="form-label mt-3">Nome do colaborador</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <select class="form-select form-select-sm mb-3 mt-3" aria-label=".form-select-lg example" > 
                    <option value="<?php echo $_SESSION['secaoDesc'];?>"><?php echo $_SESSION['secaoDesc'];?></option>
            </select>        
            <div class="mt-5">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label col-md-2" for="flexCheckDefault"> Abrir Periodo</label>
                <input class="form-check-input col-md-3" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label col-md-1" for="flexCheckDefault"> Aprovar </label>
            </div>
            
            
        </div> -->
    </body>
    <script>
        window.addEventListener("load",inicia);

    </script>
    </html>