import ApiClient, { User } from './apiClient';

/** Retorna os dados do usuário autenticado */
export async function fetchCurrentUser(): Promise<User> {
  return ApiClient.getMe();
}

/** Retorna lista de todos os usuários (requer role=admin) */
export async function fetchAllUsers(): Promise<User[]> {
  return ApiClient.listUsers();
}

/** Helper que tenta obter o usuário atual e retorna null em caso de 401/404 */
export async function safeGetCurrentUser(): Promise<User | null> {
  try {
    return await fetchCurrentUser();
  } catch (err: any) {
    if (err && (err.status === 401 || err.status === 404)) return null;
    throw err;
  }
}

export default {
  fetchCurrentUser,
  fetchAllUsers,
  safeGetCurrentUser,
};
