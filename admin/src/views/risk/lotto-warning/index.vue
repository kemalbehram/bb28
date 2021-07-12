<template>
    <div>
        <data-fix class="mb-20" ref="create" />
        <table-data class="mb-20" />
        <table-delay />
    </div>
</template>

<script>
import dataFix from './data-fix'
import tableData from './table-data'
import tableDelay from './table-delay'
export default {
    name: 'riskLottoWarning',
    components: {
        tableData,
        tableDelay,
        dataFix
    }
}
</script>
