import ApiClient, { Fornecedor } from './apiClient';

export interface CreateFornecedorData {
  nome: string;
  whatsapp?: string;
  email?: string;
}

export interface UpdateFornecedorData {
  nome?: string;
  whatsapp?: string;
  email?: string;
}

/** Lista todos os fornecedores (autenticado) */
export async function fetchFornecedores(): Promise<Fornecedor[]> {
  return ApiClient.listFornecedores();
}

/** Cria um novo fornecedor (admin) */
export async function createFornecedor(data: CreateFornecedorData) {
  return ApiClient.createFornecedor(data);
}

/** Atualiza fornecedor (admin) */
export async function updateFornecedor(id: number, data: UpdateFornecedorData) {
  return ApiClient.updateFornecedor(id, data);
}

/** Deleta fornecedor (admin) */
export async function deleteFornecedor(id: number) {
  return ApiClient.deleteFornecedor(id);
}

/** Versão segura que retorna [] em caso de 401 */
export async function safeFetchFornecedores(): Promise<Fornecedor[]> {
  try {
    return await fetchFornecedores();
  } catch (err: any) {
    if (err && err.status === 401) return [];
    throw err;
  }
}

export default {
  fetchFornecedores,
  createFornecedor,
  updateFornecedor,
  deleteFornecedor,
  safeFetchFornecedores,
};
