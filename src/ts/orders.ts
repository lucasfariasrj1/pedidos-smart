import ApiClient, { Pedido } from './apiClient';

export interface CreateOrderData {
  peca: string;
  fornecedor_id?: number;
  observacao?: string;
}

export interface UpdateOrderData {
  status?: string;
}

/** Lista pedidos. Admins recebem todos. */
export async function fetchOrders(): Promise<Pedido[]> {
  return ApiClient.listOrders();
}

/** Cria um novo pedido */
export async function createOrder(data: CreateOrderData) {
  return ApiClient.createOrder(data);
}

/** Atualiza o status do pedido (admin) */
export async function updateOrder(id: number, data: UpdateOrderData) {
  return ApiClient.updateOrder(id, data);
}

/** Deleta um pedido (admin) */
export async function deleteOrder(id: number) {
  return ApiClient.deleteOrder(id);
}

/** Versão segura que retorna [] em caso de 401 */
export async function safeFetchOrders(): Promise<Pedido[]> {
  try {
    return await fetchOrders();
  } catch (err: any) {
    if (err && err.status === 401) return [];
    throw err;
  }
}

export default {
  fetchOrders,
  createOrder,
  updateOrder,
  deleteOrder,
  safeFetchOrders,
};
