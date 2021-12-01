export const currentUser = state => state.currentUser
export const isCustomer = state =>
  state.currentUser.role == 'customer' ? true : false
