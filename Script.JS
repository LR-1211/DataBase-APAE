document.addEventListener("DOMContentLoaded", () => {
    const tabs = document.querySelectorAll(".tab-btn");
    const contents = document.querySelectorAll(".tab-content");
  
    // Troca de abas
    tabs.forEach(tab => {
      tab.addEventListener("click", () => {
        tabs.forEach(t => t.classList.remove("active"));
        contents.forEach(c => c.classList.remove("active"));
  
        tab.classList.add("active");
        document.getElementById(tab.dataset.tab).classList.add("active");
      });
    });
  
    // Carrega pacientes
    function carregarPacientes() {
      fetch("php/api.php?action=pacientes")
        .then(res => res.json())
        .then(data => {
          const lista = document.getElementById("lista-pacientes");
          lista.innerHTML = data.map(p => `
            <p><strong>${p.nome}</strong> | CPF: ${p.CPF} | Nascimento: ${p.data_nascimento} | RG: ${p.rg} | Responsável: ${p.nome_responsavel} | Tel Resp: ${p.telefone_responsavel}</p>
          `).join("");
        });
    }
  
    // Carrega profissionais
    function carregarProfissionais() {
      fetch("php/api.php?action=profissionais")
        .then(res => res.json())
        .then(data => {
          const lista = document.getElementById("lista-profissionais");
          lista.innerHTML = data.map(p => `
            <p><strong>${p.nome}</strong> | Cargo: ${p.cargo} | Especialidade: ${p.especialidade}</p>
          `).join("");
        });
    }
  
    // Carrega presença
    function carregarPresenca() {
      fetch("php/api.php?action=presenca")
        .then(res => res.json())
        .then(data => {
          const lista = document.getElementById("lista-presenca");
          lista.innerHTML = data.map(p => `
            <p><strong>ID Paciente:</strong> ${p.id_paciente} | Vinda: ${p.vinda ? "Sim" : "Não"} | Data: ${p.data_hora}</p>
          `).join("");
        });
    }
  
    // Carrega familiares
    function carregarFamiliares() {
      fetch("php/api.php?action=familiares")
        .then(res => res.json())
        .then(data => {
          const lista = document.getElementById("lista-familiares");
          lista.innerHTML = data.map(f => `
            <p><strong>${f.nome}</strong> (${f.parentesco}) | Paciente ID: ${f.id_paciente}</p>
          `).join("");
        });
    }
  
    // Formulário de pacientes
    const formPaciente = document.getElementById("form-paciente");
    formPaciente.addEventListener("submit", e => {
      e.preventDefault();
      const formData = new FormData(formPaciente);
      fetch("php/api.php?action=adicionar_paciente", {
        method: "POST",
        body: formData
      })
        .then(res => res.text())
        .then(msg => {
          alert(msg);
          formPaciente.reset();
          carregarPacientes();
        });
    });
  
    // Carregamento inicial
    carregarPacientes();
    carregarProfissionais();
    carregarPresenca();
    carregarFamiliares();
// Familiar
document.getElementById("form-familiar").addEventListener("submit", e => {
    e.preventDefault();
    const data = new FormData(e.target);
    fetch("php/api.php?action=adicionar_familiar", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(msg => {
      alert(msg);
      e.target.reset();
      carregarFamiliares();
    });
  });
  
  // Presença
  document.getElementById("form-presenca").addEventListener("submit", e => {
    e.preventDefault();
    const data = new FormData(e.target);
    fetch("php/api.php?action=adicionar_presenca", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(msg => {
      alert(msg);
      e.target.reset();
      carregarPresenca();
    });
  });
  
  // Profissional
  document.getElementById("form-profissional").addEventListener("submit", e => {
    e.preventDefault();
    const data = new FormData(e.target);
    fetch("php/api.php?action=adicionar_profissional", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(msg => {
      alert(msg);
      e.target.reset();
      carregarProfissionais();
    });
  });
  
  });
  