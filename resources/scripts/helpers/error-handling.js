import { useAuthStore } from '@/scripts/stores/auth'
import { useNotificationStore } from '@/scripts/stores/notification'

export const handleError = (err) => {
  const authStore = useAuthStore()
  const notificationStore = useNotificationStore()

  if (!err.response) {
    notificationStore.showNotification({
      type: 'error',
      message:
        'Please check your internet connection or wait until servers are back online.',
    })
  } else {
    if (
      err.response.data &&
      (err.response.statusText === 'Unauthorized' ||
        err.response.data === ' Unauthorized.')
    ) {
      // Unauthorized and log out
      const msg = err.response.data.message
        ? err.response.data.message
        : 'Unauthorized'

      showToaster(msg)

      authStore.logout()
    } else if (err.response.data.errors) {
      // Show a notification per error
      const errors = JSON.parse(JSON.stringify(err.response.data.errors))
      for (const i in errors) {
        showError(errors[i][0])
      }
    } else if (err.response.data.error) {
      showError(err.response.data.error)
    } else {
      showError(err.response.data.message)
    }
  }
}

export const showError = (error) => {
  switch (error) {
    case 'These credentials do not match our records.':
      showToaster('errors.login_invalid_credentials')
      break
    case 'invalid_key':
      showToaster('errors.invalid_provider_key')
      break

    case 'This feature is available on Starter plan and onwards!':
      showToaster('errors.starter_plan')
      break

    case 'taxes_attached':
      showToaster('settings.tax_types.already_in_use')
      break

    case 'expense_attached':
      showToaster('settings.expense_category.already_in_use')
      break

    case 'payments_attached':
      showToaster('settings.payment_modes.already_in_use')
      break

    case 'role_attached_to_users':
      showToaster('settings.roles.already_in_use')
      break

    case 'items_attached':
      showToaster('settings.customization.items.already_in_use')
      break

    case 'payment_attached_message':
      showToaster('invoices.payment_attached_message')
      break

    case 'The email has already been taken.':
      showToaster('validation.email_already_taken')
      break

    case 'Relation estimateItems exists.':
      showToaster('items.item_attached_message')
      break

    case 'Relation invoiceItems exists.':
      showToaster('items.item_attached_message')
      break

    case 'Relation taxes exists.':
      showToaster('settings.tax_types.already_in_use')
      break

    case 'Relation taxes exists.':
      showToaster('settings.tax_types.already_in_use')
      break

    case 'Relation payments exists.':
      showToaster('errors.payment_attached')
      break

    case 'The estimate number has already been taken.':
      showToaster('errors.estimate_number_used')
      break

    case 'The payment number has already been taken.':
      showToaster('errors.estimate_number_used')
      break

    case 'The invoice number has already been taken.':
      showToaster('errors.invoice_number_used')
      break

    case 'The name has already been taken.':
      showToaster('errors.name_already_taken')
      break

    case 'total_invoice_amount_must_be_more_than_paid_amount':
      showToaster('invoices.invalid_due_amount_message')
      break

    case 'you_cannot_edit_currency':
      showToaster('customers.edit_currency_not_allowed')
      break

    case 'receipt_does_not_exist':
      showToaster('errors.receipt_does_not_exist')
      break

    case 'customer_cannot_be_changed_after_payment_is_added':
      showToaster('errors.customer_cannot_be_changed_after_payment_is_added')
      break

    case 'invalid_credentials':
      showToaster('errors.invalid_credentials')
      break

    case 'not_allowed':
      showToaster('errors.not_allowed')
      break

    default:
      showToaster(error, false)
      break
  }
}

export const showToaster = (msg, t = true) => {
  const { global } = window.i18n
  const notificationStore = useNotificationStore()

  notificationStore.showNotification({
    type: 'error',
    message: t ? global.t(msg) : msg,
  })
}
