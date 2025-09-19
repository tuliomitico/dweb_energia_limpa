document.addEventListener("DOMContentLoaded", () => {
  // 1. Funcionalidade de "Ver mais"
  const applySeeMoreLogic = () => {
    document.querySelectorAll(".ver-mais").forEach((button) => {
      button.addEventListener("click", () => {
        const text = button.previousElementSibling;
        text.classList.toggle("expanded");
        button.textContent = text.classList.contains("expanded")
          ? "Ver menos"
          : "Ver mais";
      });
    });
  };

  applySeeMoreLogic(); // Aplica a lógica para os cards estáticos na index.html

  // 2. Conectando o Formulário de Cadastro
  const formCadastro = document.querySelector("main form");
  if (formCadastro) {
    formCadastro.addEventListener("submit", async (event) => {
      event.preventDefault();

      const nome = document.getElementById("nome").value;
      const email = document.getElementById("email").value;
      const senha = document.getElementById("senha").value;
      const confirmaSenha = document.getElementById("confirma-senha").value;

      const role = document.querySelector('input[name="role"]:checked')?.value;

      if (senha !== confirmaSenha) {
        alert("As senhas não coincidem!");
        return;
      }

      if (!role) {
        alert("Selecione um tipo de usuário.");
        return;
      }

      const userData = { nome, email, senha, role };

      console.log("Payload enviado: ", userData);

      try {
        const response = await fetch("apiuser.php?action=cadastrar", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(userData),
        });

        const result = await response.json();
        if (result.success) {
          alert("Usuário cadastrado com sucesso!");
          window.location.href = "login.html";
        } else {
          alert("Erro ao cadastrar usuário. Tente novamente.");
        }
      } catch (error) {
        console.error("Erro na requisição:", error);
        alert("Ocorreu um erro na comunicação com o servidor.");
      }
    });
  }

  // 3. Conectando o Formulário de Login
  const formLogin = document.querySelector(".login-container .login-box");
  if (formLogin) {
    formLogin.addEventListener("submit", async (event) => {
      event.preventDefault();

      const email = document.getElementById("email").value;
      const senha = document.getElementById("senha").value;

      const loginData = { email, senha };

      try {
        const response = await fetch("apiuser.php?action=login", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(loginData),
        });

        const result = await response.json();
        if (result.success) {
          alert("Login realizado com sucesso!");
          window.location.href = "index.html";
        } else {
          alert("Falha no login: " + result.message);
        }
      } catch (error) {
        console.error("Erro na requisição:", error);
        alert("Ocorreu um erro na comunicação com o servidor.");
      }
    });
  }

  // 4. Dinamizando o Fórum (Listar e Cadastrar posts)
  if (window.location.pathname.includes("forum.html")) {
    // Função para buscar e listar os posts
    const postsContainer = document.querySelector(".row.g-4");
    async function listarPosts() {
      try {
        const response = await fetch("apiitens.php?action=listar");
        const posts = await response.json();

        postsContainer.innerHTML = ""; // Limpa os cards de exemplo

        posts.forEach((post) => {
          const postCard = `
                        <div class="col-md-4">
                            <div class="content-card card-1 p-3 border rounded shadow-sm">
                                <h5>${post.nome}</h5>
                                <p class="card-text">${post.descricao}</p>
                                <button class="btn btn-sm btn-outline-primary ver-mais mb-3">Ver mais</button>
                            </div>
                        </div>
                    `;
          postsContainer.innerHTML += postCard;
        });

        // Aplica a lógica "Ver mais" para os novos cards
        applySeeMoreLogic();
      } catch (error) {
        console.error("Erro ao listar posts:", error);
        postsContainer.innerHTML = `<p class="text-danger">Não foi possível carregar os posts.</p>`;
      }
    }
    listarPosts();

    // Conecta o formulário de nova postagem
    const formPostagem = document.getElementById("formPostagem");
    if (formPostagem) {
      formPostagem.addEventListener("submit", async (event) => {
        event.preventDefault();

        const titulo = document.getElementById("titulo").value;
        const conteudo = document.getElementById("conteudo").value;

        const postData = { nome: titulo, descricao: conteudo };

        try {
          const response = await fetch("apiitens.php?action=cadastrar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(postData),
          });

          const result = await response.json();
          if (result.success) {
            alert("Postagem publicada com sucesso!");
            formPostagem.reset();
            listarPosts();
          } else {
            alert("Erro ao publicar postagem. Tente novamente.");
          }
        } catch (error) {
          console.error("Erro na requisição:", error);
          alert("Ocorreu um erro na comunicação com o servidor.");
        }
      });
    }
  }
});
