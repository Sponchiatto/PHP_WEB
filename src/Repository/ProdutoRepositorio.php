<?php

class ProdutoRepositorio
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function formarObjeto($dados)
    {
        return new Produto(
            $dados['id'],
            $dados['tipo'],
            $dados['nome'],
            $dados['descricao'],
            $dados['preco'],
            $dados['imagem'],
        );
    }

    public function opcoesCafe(): array
    {
        $sql1 = "SELECT * FROM produtos WHERE tipo = 'Café' ORDER BY preco";
        $statement = $this->pdo->query($sql1);
        $produtosCafe = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Transformando os produtos do café em objetos da classe Produto
        $dadosCafe = array_map(function ($cafe) {
            return new Produto($cafe["id"], $cafe["tipo"], $cafe["nome"], $cafe["descricao"], $cafe["imagem"], $cafe["preco"]);
        }, $produtosCafe);

        return $dadosCafe;
    }

    public function opcoesAlmoco(): array
    {
        $sql2 = "SELECT * FROM produtos WHERE tipo = 'Almoço' ORDER BY preco";
        $statement = $this->pdo->query($sql2);
        $produtosAlmoco = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosAlmoco = array_map(function ($almoco) {
            return $this->formarObjeto($almoco);
        }, $produtosAlmoco);

        return  $dadosAlmoco;
    }

    public function buscarTodos()
    {
        $sql = "SELECT * FROM produtos ORDER BY preco";
        $statement = $this->pdo->query($sql);
        $dados = $statement->fetchAll(mode: PDO::FETCH_ASSOC);

        $todosDados = array_map(function ($protudo) {
            return $this->formarObjeto($protudo);
        }, $dados);

        return  $todosDados;
    }

    public function deletar(int $id)
    {
        $sql = "DELETE FROM produtos WHERE id = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function salvar(Produto $produto)
    {
        $sql = "INSERT INTO produtos (tipo, nome, descricao, preco, imagem) VALUES 
        (:tipo, :nome, :descricao, :preco, :imagem)";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':tipo', $produto->getTipo(), PDO::PARAM_STR);
        $statement->bindValue(':nome', $produto->getNome(), PDO::PARAM_STR);
        $statement->bindValue(':descricao', $produto->getDescricao(), PDO::PARAM_STR);
        $statement->bindValue(':preco', $produto->getPreco(), PDO::PARAM_STR);
        $statement->bindValue(':imagem', $produto->getImagem(), PDO::PARAM_STR);
        $statement->execute();
    }
}
