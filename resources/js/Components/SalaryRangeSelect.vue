<script setup>
import { ref, computed } from 'vue'
import Multiselect from '@vueform/multiselect'
import '@vueform/multiselect/themes/default.css'

const props = defineProps({
  salaryRanges: {
    type: Array,
    required: true
  },
  modelValue: {
    type: [String, Number],
    default: ''
  }
})

const selected = ref(props.modelValue)

const formattedRanges = computed(() => {
  return props.salaryRanges.map(range => ({
    value: range.id,
    label: `${formatCurrency(range.salary_min)} - ${formatCurrency(range.salary_max)}`
  }))
})

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value)
}
</script>

<template>
    <div class="form-group">
        <label class="block text-sm font-medium text-gray-700">
        Pilih Range Gaji
        </label>
        
        <Multiselect
        v-model="selected"
        :options="formattedRanges"
        :searchable="false"
        class="mt-1"
        placeholder="Pilih Range Gaji"
        @change="$emit('update:modelValue', selected)"
        />
    </div>
</template>