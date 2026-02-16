(function () {
  const API_BASE = '../api/index.php?url=';

  function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) {
      return parts.pop().split(';').shift();
    }
    return null;
  }

  function getToken() {
    return localStorage.getItem('jwt_token') || getCookie('jwt_token');
  }

  function parseJwt(token) {
    try {
      const payload = token.split('.')[1];
      if (!payload) {
        return null;
      }

      return JSON.parse(atob(payload.replace(/-/g, '+').replace(/_/g, '/')));
    } catch (_) {
      return null;
    }
  }

  function showFeedback(message, type = 'success') {
    const alertClass = type === 'error' ? 'danger' : type;
    const container = document.getElementById('feedback-container') || document.body;
    const wrapper = document.createElement('div');
    wrapper.className = `alert alert-${alertClass} alert-dismissible fade show`;
    wrapper.role = 'alert';
    wrapper.style.position = 'fixed';
    wrapper.style.top = '20px';
    wrapper.style.right = '20px';
    wrapper.style.zIndex = '1060';
    wrapper.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
    container.appendChild(wrapper);
    setTimeout(() => wrapper.remove(), 3500);
  }

  async function apiRequest(endpoint, method = 'GET', body) {
    const token = getToken();
    const headers = { 'Content-Type': 'application/json' };

    if (token) {
      headers.Authorization = `Bearer ${token}`;
    }

    const response = await fetch(`${API_BASE}${endpoint}`, {
      method,
      headers,
      body: body ? JSON.stringify(body) : undefined,
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
      throw new Error(data.error || 'Erro ao processar requisição');
    }

    return data;
  }

  window.apiClient = {
    get: (endpoint) => apiRequest(endpoint),
    post: (endpoint, payload) => apiRequest(endpoint, 'POST', payload),
    put: (endpoint, payload) => apiRequest(endpoint, 'PUT', payload),
    del: (endpoint) => apiRequest(endpoint, 'DELETE'),
  };

  window.salvarPedido = async function salvarPedido() {
    const form = document.getElementById('pedido-form');
    if (!form) {
      return;
    }

    const formData = new FormData(form);
    const payload = Object.fromEntries(formData.entries());

    payload.quantidade = Number(payload.quantidade);
    payload.preco_fornecedor = Number(payload.preco_fornecedor);

    try {
      await window.apiClient.post('pedidos', payload);
      showFeedback('Pedido salvo com sucesso.');
      form.reset();
    } catch (error) {
      showFeedback(error.message, 'error');
    }
  };

  async function carregarPedidos() {
    const tbody = document.getElementById('pedidos-table-body');
    if (!tbody) {
      return;
    }

    try {
      const pedidos = await window.apiClient.get('pedidos');
      tbody.innerHTML = '';

      pedidos.forEach((pedido) => {
        const total = Number(pedido.preco_fornecedor || 0) * Number(pedido.quantidade || 0);
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>#${pedido.id}</td>
          <td>${pedido.data_pedido || '-'}</td>
          <td><strong>${pedido.modelo_celular || '-'}</strong><br><small class="text-muted">${pedido.nome_peca || '-'}</small></td>
          <td>${pedido.quantidade || 0}</td>
          <td>${pedido.fornecedor_nome || '-'}</td>
          <td>R$ ${total.toFixed(2)}</td>
          <td class="text-center">-</td>
        `;
        tbody.appendChild(row);
      });
    } catch (error) {
      showFeedback(error.message, 'error');
    }
  }

  async function carregarFornecedoresSelect() {
    const select = document.querySelector('select[name="fornecedor_id"]');
    if (!select) {
      return;
    }

    try {
      const fornecedores = await window.apiClient.get('fornecedores');
      select.innerHTML = '<option value="">Selecione um fornecedor</option>';
      fornecedores.forEach((fornecedor) => {
        const option = document.createElement('option');
        option.value = fornecedor.id;
        option.textContent = fornecedor.nome;
        select.appendChild(option);
      });
    } catch (_) {
      // Ignora para não bloquear a tela
    }
  }

  async function crudPageSetup(config) {
    const tableBody = document.querySelector(config.tableBodySelector);
    const form = document.querySelector(config.formSelector);
    if (!tableBody || !form) {
      return;
    }

    const render = (rows) => {
      tableBody.innerHTML = '';
      rows.forEach((row) => {
        const tr = document.createElement('tr');
        tr.innerHTML = config.renderRow(row);
        tableBody.appendChild(tr);
      });
    };

    const reload = async () => {
      try {
        const rows = await window.apiClient.get(config.endpoint);
        render(rows);
      } catch (error) {
        showFeedback(error.message, 'error');
      }
    };

    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      const data = Object.fromEntries(new FormData(form).entries());
      const id = form.dataset.editId;

      try {
        if (id) {
          await window.apiClient.put(`${config.endpoint}/${id}`, data);
          showFeedback('Registro atualizado com sucesso.');
          delete form.dataset.editId;
        } else {
          await window.apiClient.post(config.endpoint, data);
          showFeedback('Registro criado com sucesso.');
        }
        form.reset();
        await reload();
      } catch (error) {
        showFeedback(error.message, 'error');
      }
    });

    tableBody.addEventListener('click', async (event) => {
      const editBtn = event.target.closest('[data-action="edit"]');
      const deleteBtn = event.target.closest('[data-action="delete"]');

      if (editBtn) {
        const data = JSON.parse(editBtn.dataset.row);
        Object.entries(data).forEach(([key, value]) => {
          const field = form.querySelector(`[name="${key}"]`);
          if (field) {
            field.value = value ?? '';
          }
        });
        form.dataset.editId = data.id;
      }

      if (deleteBtn) {
        const id = deleteBtn.dataset.id;
        if (!confirm('Deseja excluir este registro?')) {
          return;
        }
        try {
          await window.apiClient.del(`${config.endpoint}/${id}`);
          showFeedback('Registro excluído com sucesso.');
          await reload();
        } catch (error) {
          showFeedback(error.message, 'error');
        }
      }
    });

    await reload();
  }

  async function initUsuarios() {
    await crudPageSetup({
      endpoint: 'usuarios',
      tableBodySelector: '#usuarios-table-body',
      formSelector: '#usuario-form',
      renderRow: (row) => `
        <td>${row.id}</td>
        <td>${row.nome}</td>
        <td>${row.email}</td>
        <td>${row.role}</td>
        <td>${row.loja_nome || '-'}</td>
        <td class="text-end">
          <button class="btn btn-sm btn-light border" data-action="edit" data-row='${JSON.stringify(row)}'><i class="bi bi-pencil"></i></button>
          <button class="btn btn-sm btn-light border text-danger" data-action="delete" data-id="${row.id}"><i class="bi bi-trash"></i></button>
        </td>
      `,
    });
  }

  async function initFornecedores() {
    await crudPageSetup({
      endpoint: 'fornecedores',
      tableBodySelector: '#fornecedores-table-body',
      formSelector: '#fornecedor-form',
      renderRow: (row) => `
        <td>${row.id}</td>
        <td>${row.nome}</td>
        <td>${row.whatsapp || '-'}</td>
        <td>${row.email || '-'}</td>
        <td class="text-end">
          <button class="btn btn-sm btn-light border" data-action="edit" data-row='${JSON.stringify(row)}'><i class="bi bi-pencil"></i></button>
          <button class="btn btn-sm btn-light border text-danger" data-action="delete" data-id="${row.id}"><i class="bi bi-trash"></i></button>
        </td>
      `,
    });
  }

  async function initLojas() {
    await crudPageSetup({
      endpoint: 'lojas',
      tableBodySelector: '#lojas-table-body',
      formSelector: '#loja-form',
      renderRow: (row) => `
        <td>${row.id}</td>
        <td>${row.nome}</td>
        <td>${row.endereco || '-'}</td>
        <td>${Number(row.ativo) === 1 ? 'Ativa' : 'Inativa'}</td>
        <td class="text-end">
          <button class="btn btn-sm btn-light border" data-action="edit" data-row='${JSON.stringify(row)}'><i class="bi bi-pencil"></i></button>
          <button class="btn btn-sm btn-light border text-danger" data-action="delete" data-id="${row.id}"><i class="bi bi-trash"></i></button>
        </td>
      `,
    });
  }

  async function initSettings() {
    const settingsForm = document.getElementById('global-settings-form');
    if (!settingsForm) {
      return;
    }

    const loadSettings = async () => {
      const settings = await window.apiClient.get('settings');
      settings.forEach((setting) => {
        const field = settingsForm.querySelector(`[name="${setting.chave}"]`);
        if (!field) {
          return;
        }

        if (field.type === 'checkbox') {
          field.checked = Number(setting.valor) === 1 || setting.valor === 'true';
        } else {
          field.value = setting.valor ?? '';
        }
      });
    };

    settingsForm.addEventListener('submit', async (event) => {
      event.preventDefault();
      const payload = {
        nome_sistema: settingsForm.querySelector('[name="nome_sistema"]').value,
        modo_manutencao: settingsForm.querySelector('[name="modo_manutencao"]').checked ? 1 : 0,
        logo_url: settingsForm.querySelector('[name="logo_url"]').value,
        limite_pedidos_loja: Number(settingsForm.querySelector('[name="limite_pedidos_loja"]').value || 0),
      };

      try {
        await window.apiClient.put('settings', payload);
        showFeedback('Configurações globais atualizadas com sucesso.');
      } catch (error) {
        showFeedback(error.message, 'error');
      }
    });

    try {
      await loadSettings();
    } catch (error) {
      showFeedback(error.message, 'error');
    }

    await initLojas();
  }

  function initStoreInfo() {
    const token = getToken();
    const payload = token ? parseJwt(token) : null;
    if (!payload) {
      return;
    }

    const storeName = payload.loja_nome || `Loja #${payload.loja_id ?? '-'}`;
    const lojaElements = document.querySelectorAll('[data-store-name]');
    lojaElements.forEach((el) => {
      el.textContent = storeName;
    });

    const userElements = document.querySelectorAll('[data-user-name]');
    userElements.forEach((el) => {
      el.textContent = payload.nome || payload.email || 'Usuário';
    });
  }

  function initLogout() {
    const logoutButton = document.getElementById('logout-button');
    if (!logoutButton) {
      return;
    }

    logoutButton.addEventListener('click', async () => {
      try {
        await window.apiClient.post('logout', {});
      } catch (_) {
        // segue logout local
      }

      localStorage.removeItem('jwt_token');
      localStorage.removeItem('user_payload');
      document.cookie = 'jwt_token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT';
      window.location.href = 'login.php';
    });
  }

  document.addEventListener('DOMContentLoaded', async () => {
    initStoreInfo();
    initLogout();

    const pedidoForm = document.getElementById('pedido-form');
    if (pedidoForm) {
      pedidoForm.addEventListener('submit', (event) => {
        event.preventDefault();
        window.salvarPedido();
      });
      await carregarFornecedoresSelect();
    }

    await carregarPedidos();

    if (document.getElementById('usuarios-table-body')) {
      await initUsuarios();
    }

    if (document.getElementById('fornecedores-table-body')) {
      await initFornecedores();
    }

    if (document.getElementById('lojas-table-body') && !document.getElementById('global-settings-form')) {
      await initLojas();
    }

    if (document.getElementById('global-settings-form')) {
      await initSettings();
    }
  });
})();
