document.addEventListener("DOMContentLoaded", () => {
  const tabs = document.querySelectorAll(".tab-btn");
  const contents = document.querySelectorAll(".tab-content");

  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      tabs.forEach(t => t.classList.remove("active"));
      contents.forEach(c => c.classList.remove("active"));

      tab.classList.add("active");
      document.getElementById(tab.dataset.tab).classList.add("active");
    });
  });

  function carregarPacientes() {
    fetch("/php/api.php?action=pacientes")
      .then(res => {
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
      })
      .then(data => {
        const lista = document.getElementById("lista-pacientes");
        lista.innerHTML = data.map(p => `
          <p><strong>${p.nome}</strong> | CPF: ${p.CPF} | Nascimento: ${p.data_nascimento} | RG: ${p.rg} | Responsável: ${p.nome_responsavel} | Tel Resp: ${p.telefone_responsavel}</p>
        `).join("");
      })
      .catch(err => console.error("Erro ao carregar pacientes:", err));
  }

  function carregarProfissionais() {
    fetch("/php/api.php?action=profissionais")
      .then(res => {
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
      })
      .then(data => {
        const lista = document.getElementById("lista-profissionais");
        lista.innerHTML = data.map(p => `
          <p><strong>${p.nome}</strong> | Cargo: ${p.cargo} | Especialidade: ${p.especialidade}</p>
        `).join("");
      })
      .catch(err => console.error("Erro ao carregar profissionais:", err));
  }

  function carregarPresenca() {
    fetch("/php/api.php?action=presenca")
      .then(res => {
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
      })
      .then(data => {
        const lista = document.getElementById("lista-presenca");
        lista.innerHTML = data.map(p => `
          <p><strong>ID Paciente:</strong> ${p.id_paciente} | Vinda: ${p.vinda ? "Sim" : "Não"} | Data: ${p.data_hora}</p>
        `).join("");
      })
      .catch(err => console.error("Erro ao carregar presença:", err));
  }

  function carregarFamiliares() {
    fetch("/php/api.php?action=familiares")
      .then(res => {
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
      })
      .then(data => {
        const lista = document.getElementById("lista-familiares");
        lista.innerHTML = data.map(f => `
          <p><strong>${f.nome}</strong> (${f.parentesco}) | Paciente ID: ${f.id_paciente}</p>
        `).join("");
      })
      .catch(err => console.error("Erro ao carregar familiares:", err));
  }

  function tratarResposta(res) {
    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
    return res.json();
  }

  function tratarRespostaAPI(data, form) {
    if(data.msg) {
      alert(data.msg);
      form.reset();
      return true;
    } else if(data.erro) {
      alert("Erro: " + data.erro);
      return false;
    } else {
      alert("Resposta inesperada do servidor");
      return false;
    }
  }

  // Paciente
  const formPaciente = document.getElementById("form-paciente");
  formPaciente.addEventListener("submit", e => {
    e.preventDefault();
    const formData = new FormData(formPaciente);

    fetch("/php/api.php?action=adicionar_paciente", {
      method: "POST",
      body: formData
    })
    .then(tratarResposta)
    .then(data => {
      if(tratarRespostaAPI(data, formPaciente)) {
        carregarPacientes();
      }
    })
    .catch(err => {
      console.error("Erro na requisição paciente:", err);
      alert("Erro na requisição");
    });
  });

  // Familiar
  document.getElementById("form-familiar").addEventListener("submit", e => {
    e.preventDefault();
    const data = new FormData(e.target);

    fetch("/php/api.php?action=adicionar_familiar", {
      method: "POST",
      body: data
    })
    .then(tratarResposta)
    .then(data => {
      if(tratarRespostaAPI(data, e.target)) {
        carregarFamiliares();
      }
    })
    .catch(err => {
      console.error("Erro na requisição familiar:", err);
      alert("Erro na requisição");
    });
  });

  // Presença
  document.getElementById("form-presenca").addEventListener("submit", e => {
    e.preventDefault();
    const data = new FormData(e.target);

    fetch("/php/api.php?action=adicionar_presenca", {
      method: "POST",
      body: data
    })
    .then(tratarResposta)
    .then(data => {
      if(tratarRespostaAPI(data, e.target)) {
        carregarPresenca();
      }
    })
    .catch(err => {
      console.error("Erro na requisição presença:", err);
      alert("Erro na requisição");
    });
  });

  // Profissional
  document.getElementById("form-profissional").addEventListener("submit", e => {
    e.preventDefault();
    const data = new FormData(e.target);

    fetch("/php/api.php?action=adicionar_profissional", {
      method: "POST",
      body: data
    })
    .then(tratarResposta)
    .then(data => {
      if(tratarRespostaAPI(data, e.target)) {
        carregarProfissionais();
      }
    })
    .catch(err => {
      console.error("Erro na requisição profissional:", err);
      alert("Erro na requisição");
    });
  });

  // Carregamento inicial
  carregarPacientes();
  carregarProfissionais();
  carregarPresenca();
  carregarFamiliares();
});
