const BASE_URL = 'https://api.assistenciasmarthard.com.br/';

export interface User {
  id: number;
  email: string;
  role: string;
  loja_id: number;
  last_login?: string;
  updated_at?: string;
  created_at?: string;
  token?: string;
}

export interface Pedido {
  id: number;
  loja_id: number;
  usuario_id: number;
  fornecedor_id: number;
  peca: string;
  observacao?: string;
  status?: string;
}

export interface Fornecedor {
  id: number;
  nome: string;
  whatsapp?: string;
  email?: string;
}

export interface LoginResponse {
  token: string;
  success?: boolean;
  message?: string;
  user?: User;
}

function getStoredToken(): string | null {
  try {
    return localStorage.getItem('api_token');
  } catch (e) {
    return null;
  }
}

function setStoredToken(token: string | null) {
  try {
    if (token) localStorage.setItem('api_token', token);
    else localStorage.removeItem('api_token');
  } catch (e) {
    // ignore
  }
}

async function request<T>(path: string, options: RequestInit = {}): Promise<T> {
  const headers: Record<string, string> = {
    'Content-Type': 'application/json',
    ...(options.headers as Record<string, string> || {}),
  };

  const token = getStoredToken();
  if (token) headers['Authorization'] = `Bearer ${token}`;

  const res = await fetch(new URL(path, BASE_URL).toString(), {
    ...options,
    headers,
  });

  if (!res.ok) {
    const text = await res.text();
    let body: any = text;
    try { body = JSON.parse(text); } catch (e) {}
    throw { status: res.status, body };
  }

  const contentType = res.headers.get('content-type') || '';
  if (contentType.includes('application/json')) return res.json();
  // @ts-ignore
  return (await res.text()) as T;
}

export const ApiClient = {
  setToken: (token: string | null) => setStoredToken(token),
  getToken: () => getStoredToken(),

  // Auth
  login: async (email: string, senha: string): Promise<LoginResponse> => {
    const resp = await request<LoginResponse>('/auth/login', {
      method: 'POST',
      body: JSON.stringify({ email, senha }),
    });
    if (resp && (resp as any).token) setStoredToken((resp as any).token);
    return resp;
  },

  register: async (email: string, senha: string, role: string, loja_id: number): Promise<any> => {
    const resp = await request('/auth/register', {
      method: 'POST',
      body: JSON.stringify({ email, senha, role, loja_id }),
    });
    if (resp && (resp as any).token) setStoredToken((resp as any).token);
    return resp;
  },

  logout: async () => {
    setStoredToken(null);
    try { await request('/auth/logout', { method: 'POST' }); } catch (e) {}
  },

  // Users
  getMe: async (): Promise<User> => request<User>('/auth/users/me'),
  listUsers: async (): Promise<User[]> => request<User[]>('/auth/users/listall'),

  // Pedidos
  listOrders: async (): Promise<Pedido[]> => request<Pedido[]>('/auth/orders'),
  createOrder: async (data: { peca: string; fornecedor_id?: number; observacao?: string }) =>
    request('/auth/orders', { method: 'POST', body: JSON.stringify(data) }),
  updateOrder: async (id: number, data: { status?: string }) =>
    request(`/auth/orders/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
  deleteOrder: async (id: number) => request(`/auth/orders/${id}`, { method: 'DELETE' }),

  // Fornecedores
  listFornecedores: async (): Promise<Fornecedor[]> => request<Fornecedor[]>('/auth/fornecedores'),
  createFornecedor: async (data: { nome: string; whatsapp?: string; email?: string }) =>
    request('/auth/fornecedores', { method: 'POST', body: JSON.stringify(data) }),
  updateFornecedor: async (id: number, data: { nome?: string; whatsapp?: string; email?: string }) =>
    request(`/auth/fornecedores/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
  deleteFornecedor: async (id: number) => request(`/auth/fornecedores/${id}`, { method: 'DELETE' }),
};

export default ApiClient;
