<template>
  <base-page>
    <dashboard-stats />
    <dashboard-chart />
    <dashboard-table />

    {{ currentUser }}
  </base-page>
</template>

<script>
import DashboardStats from '../dashboard/DashboardStats'
import DashboardChart from '../dashboard/DashboardChart'
import DashboardTable from '../dashboard/DashboardTable'
import { mapGetters } from 'vuex'

export default {
  components: {
    DashboardStats,
    DashboardChart,
    DashboardTable,
  },
  computed: {
    ...mapGetters('user', ['currentUser'])
  },
  beforeMount(){
    if (this.currentUser.role == 'customer') {
       this.$router.push('/admin/invoices')
    }
  },
}
</script>
