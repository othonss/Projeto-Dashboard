<?php
    /*
        2º Tarefa (Incluir back-end do projeto com PHP)
            - a) Criar classe responsável pela funções do projeto 
            - b) Criar classe responsável pela conexão ao banco de dados (com try catch)
            - c) Criar classe que fará a manipulação do projeto
            - d) Realizar as instâncias das classes criadas anteriormente
            - e) Criar as funções para exibir números e total de vendas 
    */

    // a)
    class Dashboard{
        public $dataInicio;
        public $dataFim;
        public $numeroVendas;
        public $totalVendas;

        public function __get($atributo){
            return $this->$atributo;
        }

        public function __set($atributo, $valor){
            $this->$atributo = $valor;
            return $this;
        }
    }

    // b)
    class Conexao {
        private $host = 'localhost';
        private $dbname = 'dashboard';
        private $user = 'root';
        private $pass = '';

        public function conectar(){
            try {
                $conexao = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname",
                    "$this->user",
                    "$this->pass"
                );
                //Para o banco obedecer a lista de caracteres disponíveis do front-end
                $conexao->exec('set charset utf8');
                return $conexao;

            } catch (PDOException $e){
                echo '<p>' . $e->getMessage() . '</p>';
            }
            
        }
    }

    // c)
    class Bd {
        private $conexao;
        private $dashboard;
        public function __construct(Conexao $conexao, Dashboard $dashboard){
            $this->conexao = $conexao->conectar();
            $this->dashboard = $dashboard;
        }

        // e)
        public function getNumeroVendas(){
            $query = '
                select
                    count(*) as numero_vendas
                from
                    tb_vendas
                where
                    data_venda between :dataInicio and :dataFim
            ';
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':dataInicio', $this->dashboard->__get('dataInicio'));
            $stmt->bindValue(':dataFim',  $this->dashboard->__get('dataFim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
        }

        public function getTotalVendas(){
            $query = '
                select
                    SUM(total) as total_vendas
                from
                    tb_vendas
                where
                    data_venda between :dataInicio and :dataFim
            ';
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':dataInicio', $this->dashboard->__get('dataInicio'));
            $stmt->bindValue(':dataFim',  $this->dashboard->__get('dataFim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
        }
    }

    // d)
    $dashboard = new Dashboard();
    $dashboard->__set('dataInicio', '2018-08-01');
    $dashboard->__set('dataFim', '2018-08-31');

    $conexao = new Conexao();
    $bd = new Bd($conexao, $dashboard);
    $dashboard->__set('numeroVendas', $bd->getNumeroVendas());
    $dashboard->__set('totalVendas', $bd->getTotalVendas());
    print_r($dashboard);
?>