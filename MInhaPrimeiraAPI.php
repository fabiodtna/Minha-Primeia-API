
<?php
 

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");

    // conexão com o banco de dados;
include("conexao.php");


// o que vem dps da ? na url
$query = $_SERVER["QUERY_STRING"];

$key = '1900';


// metodo para retorno 

if($_SERVER["REQUEST_METHOD"] == "POST"){
  
    switch($query) {

        # fazer login
        case "login":
            
            // pegar tudo que tem no post json
            $data = json_decode(file_get_contents('php://input'), true);

            if(($data['WC'] == 'b5065d11ea07323f144af5bcc068ced8')){
     

                // pegar dados quem vem  no post email senha

                $email = addslashes($data['email']);
                $senha = addslashes($data['senha']);

                //$sql = "SELECT * FROM Tb_usuarios WHERE nm_email = '$email' AND nm_senha = '$senha'";

                $sql = "SELECT * FROM tb_usuario WHERE nm_email = '$email' and nm_senha = '$senha'";
                $resultado = mysqli_query($strcon,$sql) or die("Erro ao retornar dados");

                //pegar o resultado da consulta no banco de dados
                $linha = mysqli_fetch_assoc($resultado);
                
                if($linha == null ) {

                    echo json_encode("Nao existe");
                    mysql_close();
                
                }else{

                    $nome = $linha['nm_nome'];
                    $email = $linha['nm_email'];

                    $ok = $key .=  $nome .= $email;

                    $Auth = md5($ok);

                    }
                    // transforma o resultado em json 
                    echo json_encode([$Auth]+$linha);
                    mysql_close();
            }else{
                echo json_encode("NOT FOUND 404");
            }  
          
           break;





        # fazer cadastro
        case "cadastro":

            // pegar tudo que tem no post json
            $data = json_decode(file_get_contents('php://input'), true);
            

            if(($data['WC'] == 'b5065d11ea07323f144af5bcc068ced8')){

                // pegandos dados do post 
                $cidade = addslashes(($data['Dcidade']));
                
                $avatar = addslashes(($data['avatar']));
                
                $nome = addslashes(($data['nome']));
                
                $sobrenome = addslashes(($data['sobrenome']));
                
                $dtnascimento = addslashes(($data['dtnascimento']));
                
                $telefone = addslashes(($data['telefone']));
                
                $email = addslashes(($data['email']));
                
                $senha = md5(($data['senha']));
                
                $TC_CL = addslashes(($data['TC_CL']));
                
                $descricao = addslashes(($data['descricao']));



                // confirma se ja existe o email ou não
                $sql= "SELECT COUNT(*) FROM tb_usuario WHERE nm_email = '$email'";
                $resultado = mysqli_query($strcon,$sql) or die("Erro ao retornar dados");

                $row = $resultado->fetch_row();
                    // email ja existe
                    if ($row[0] > 0) {
                        echo json_encode("E-mail existente");
                    } else 
                    // cadastrar user
                    {
                        $sqlcad = "INSERT INTO tb_usuario (cd_user,id_cidade,nm_avatar,nm_nome,nm_sobrenome,dt_nascimento,nr_telefone,nm_email,nm_senha,TC_CL,nm_descricao )  
                        VALUES
                        (null, '".$cidade."','".$avatar."','".$nome."','".$sobrenome."','".$dtnascimento."','".$telefone."','".$email."','".$senha."','".$TC_CL."','".$descricao."')";
                        
                        $resp = mysqli_query($strcon,$sqlcad) or die("Erro ao retornar dados");
                        
                        echo json_encode("Cadastro realizado com sucesso!!");
                        mysql_close();
                    }
            
            }else{
                echo json_encode("NOT FOUND 404");
            }
        
            break;





        # fazer post
        
        case "CRpost":
            // pegar tudo que tem no post json
            $data = json_decode(file_get_contents('php://input'), true);
            $authC = addslashes(($data['token']));    
            $nome = addslashes(($data['nome']));
            $email= addslashes(($data['email']));
            $iduser = addslashes(($data['iduser']));
            $catg = addslashes(($data['catg']));
            $name = addslashes(($data['nome']));
            $descri = addslashes(($data['descri']));
            $latit = addslashes(($data['latitude']));
            $longi = addslashes(($data['logitude']));


            if((isset($data['WC']) && $data['WC'] == 'b5065d11ea07323f144af5bcc068ced8')){ 

                $ok = $key .=  $nome .= $email;

                $authS = md5($ok);

                if($authC == $authS){
    
                    $sql= "INSERT INTO tb_public(id_user, id_catg, nm_user, nm_descricao, nm_latitude, nm_logitude )  
                    VALUES 
                    ('".$iduser."','".$catg."','".$name."','".$descri."','".$latit."','".$longi."')";
                    $resultado = mysqli_query($strcon,$sql) or die(json_encode("Erro ao retornar dados"));
                    
                    echo json_encode("Post Criado");
                   
                }else{
                    echo json_encode("falhou");
                }
                
            }else{
                echo json_encode("NOT FOUND 404");
            }

            
          break; 





        # pegar feed os post quem tem no banco de dados
        case "feed":
            // pegar tudo que tem no post json
            
            $data = json_decode(file_get_contents('php://input'), true);

            if(($data['WC'] == 'b5065d11ea07323f144af5bcc068ced8')){

                $authC = ($data['token']);

                $nome = ($data['nome']);
                $email= ($data['email']);

                $ok = $key .=  $nome .= $email;

                $authS = md5($ok);

                if($authC == $authC){
                   
                    $sql = "Select cd_public, id_user ,id_catg, nm_user, nm_descricao, 
                    nm_latitude, nm_logitude from tb_public";
                    
                    $resp = mysqli_query ($strcon,$sql) or die(json_encode("Erro ao retornar dados"));
                    
                    for ($dbdata ; $row = $resp->fetch_assoc(); $dbdata[] = $row);
                    echo json_encode($dbdata);
                  
                }else{
                    echo json_encode("falhou");
                }
                
            }else{
                echo json_encode("NOT FOUND 404");
            }
        
          break;





        # editar perfil do usuario
        case "editar":

            // pegar tudo que tem no post json
            $data = json_decode(file_get_contents('php://input'), true);


            if(($data['WC'] == 'b5065d11ea07323f144af5bcc068ced8')){

                $authC = ($data['token']);

                $nome = ($data['nome']);
                $email= ($data['email']);

                $ok = $key .=  $nome .= $email;

                $authS = md5($ok);

                if($authC == $authS){
                
                    
                    $iduser = addslashes(($data['iduser']));

                    $cidade = addslashes(($data['id_cidade']));
                    
                    $telefone = addslashes(($data['telefone']));
                    
                    $senha = addslashes(($data['senha']));

                    $hashsenha = md5($senha);
                    
                    $TC_CL = addslashes(($data['TC_CL']));
                    
                    $descricao = addslashes(($data['descricao']));

                    // alterar senha
                    if ($senha != "") {

                        $sql = "UPDATE tb_usuario SET nm_senha='".$hashsenha."'
                        WHERE cd_user='".$iduser."'"; 
                        
                        $resp = mysqli_query ($strcon,$sql) or die(json_encode("Erro ao retornar dados"));
                        
                        echo json_encode("Senha Alterada ");
                    } else {
                        $sql = "UPDATE tb_usuario SET id_cidade='".$cidade."',  nr_telefone='".$telefone."', 
                        TC_CL='".$TC_CL."', nm_descricao='".$descricao."'
                        WHERE cd_user='".$iduser."'"; 
                        
                        $resp = mysqli_query ($strcon,$sql) or die(json_encode("Erro ao retornar dados"));
                        
                        echo json_encode("Alter for");
                    }

                }else{
                    echo json_encode("falhou");
                }
                
            }else{
                echo json_encode("NOT FOUND 404");
            }
            
            break;

        #buscar por post espeficicos no menu do app
        case "especif":
            // pegar tudo que tem no post json
            $data = json_decode(file_get_contents('php://input'), true);
            
            echo "post espeficos ao lado";

            break;
        
        # pesquisar com nomes especificos como marca de uma aparelho
        case "pesquisar":
            // pegar tudo que tem no post json
            $data = json_decode(file_get_contents('php://input'), true);
            
            echo "pesquisar";
            break;

        # excluir perfil do usuario que esta logado
        case "delet":
            // pegar tudo que tem no post json
            $data = json_decode(file_get_contents('php://input'), true);
            
            echo "deletar perfil";
            
            break;

        # excluir publicação do user que esta no app
        case "deletpost":
            // pegar tudo que tem no post json
            $data = json_decode(file_get_contents('php://input'), true);
            
            echo "excluir meu post";

            break;

        #Rank dos melhores tecnicos da região

        case "rank":
            // pegar tudo que tem no post json
            $data = json_decode(file_get_contents('php://input'), true);
            
            echo "Melhores tecnicos da região";

            break;

        default:
        // erro de metodo 
            echo json_encode(array(
                "status" => 404,
                "message" => "Nenhum metodo encontrado"
            ));    

            break;
    }
}else{
     echo "Nada Encontrado!";

}


?>

