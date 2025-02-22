<template>
  <BaseMultiselect
    v-model="selectedCustomer"
    v-bind="$attrs"
    track-by="name"
    value-prop="id"
    label="name"
    :filter-results="false"
    :min-chars="1"
    resolve-on-load
    :delay="500"
    :searchable="true"
    :options="searchCustomers"
    label-value="name"
    :placeholder="$t('customers.type_or_click')"
    :can-deselect="false"
    class="w-full"
  >
    <template v-if="showAction" #action>
      <BaseSelectAction
        v-if="userStore.hasAbilities(abilities.CREATE_CUSTOMER)"
        @click="addCustomer"
      >
        <BaseIcon
          name="UserAddIcon"
          class="h-4 mr-2 -ml-2 text-center text-primary-400"
        />

        {{ $t('customers.add_new_customer') }}
      </BaseSelectAction>
    </template>
  </BaseMultiselect>

  <CustomerModal />
</template>

<script setup>
import { useCustomerStore } from '@/scripts/stores/customer'
import { computed, watch } from 'vue'
import { useModalStore } from '@/scripts/stores/modal'
import { useI18n } from 'vue-i18n'
import CustomerModal from '@/scripts/components/modal-components/CustomerModal.vue'
import { useUserStore } from '@/scripts/stores/user'
import abilities from '@/scripts/stub/abilities'

const props = defineProps({
  modelValue: {
    type: [String, Number, Object],
    default: '',
  },
  fetchAll: {
    type: Boolean,
    default: false,
  },
  showAction: {
    type: Boolean,
    default: false,
  },
})

const { t } = useI18n()

const emit = defineEmits(['update:modelValue'])

const modalStore = useModalStore()
const customerStore = useCustomerStore()
const userStore = useUserStore()

const selectedCustomer = computed({
  get: () => props.modelValue,
  set: (value) => {
    emit('update:modelValue', value)
  },
})

async function searchCustomers(search) {
  let data = {
    search,
  }

  if (props.fetchAll) {
    data.limit = 'all'
  }

  let res = await customerStore.fetchCustomers(data)

  return res.data.data
}

async function addCustomer() {
  customerStore.resetCurrentCustomer()

  modalStore.openModal({
    title: t('customers.add_new_customer'),
    componentName: 'CustomerModal',
  })
}
</script>
