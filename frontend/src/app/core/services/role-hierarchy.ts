/**
 * For instance, 'ROLE_ADMIN' implicitly includes 'ROLE_COORDINATOR' and 'ROLE_WAREHOUSE'.
 */
export const FRONTEND_ROLE_HIERARCHY: Record<string, string[]> = {
  ROLE_ADMIN: ['ROLE_COORDINATOR', 'ROLE_WAREHOUSE'],
  ROLE_COORDINATOR: ['ROLE_WAREHOUSE'],
  ROLE_WAREHOUSE: []
};
