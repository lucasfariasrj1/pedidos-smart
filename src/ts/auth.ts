import ApiClient from './apiClient';

export async function loginAndStore(email: string, senha: string) {
  const res = await ApiClient.login(email, senha);
  return res;
}

export async function registerAndStore(email: string, senha: string, role: string, loja_id: number) {
  const res = await ApiClient.register(email, senha, role, loja_id);
  return res;
}

export function logout() {
  return ApiClient.logout();
}

export function isAuthenticated(): boolean {
  return !!ApiClient.getToken();
}

export async function getCurrentUser() {
  return ApiClient.getMe();
}

export default {
  loginAndStore,
  registerAndStore,
  logout,
  isAuthenticated,
  getCurrentUser,
};
