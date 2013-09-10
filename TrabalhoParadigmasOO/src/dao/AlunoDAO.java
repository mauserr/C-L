package dao;

import factory.ConnectionFactory;
import modelo.Aluno;
import java.sql.*;
import java.sql.PreparedStatement;

public class AlunoDAO {

    private Connection connection;
    Long matricula;
    String nome;
    String cpf;
    String email;

    public AlunoDAO() {
        this.connection = new ConnectionFactory().getConnection();
    }

    public void adiciona(Aluno aluno) {

        String sql = "INSERT INTO aluno(nome,cpf,email) VALUES(?,?,?)";

        try {
            PreparedStatement stmt = connection.prepareStatement(sql);

            stmt.setString(1, aluno.getNome());
            stmt.setString(2, aluno.getCpf());
            stmt.setString(3, aluno.getEmail());

            stmt.execute();
            stmt.close();

        } catch (SQLException u) {
            throw new RuntimeException(u);
        }
    }
}
