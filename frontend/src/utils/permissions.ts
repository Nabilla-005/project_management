export function can(permission: string, permissions: string[]) {
  return permissions.includes(permission)
}

export function isRole(role: string, userRole: string) {
  return userRole === role
}