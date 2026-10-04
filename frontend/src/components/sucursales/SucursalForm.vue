<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
  sucursal: { type: Object, default: null },
  guardando: { type: Boolean, default: false }
})

const emit = defineEmits(['guardar', 'cancelar'])

const formulario = reactive({
  nombre: '',
  direccion: '',
  telefono: '',
  correo: '',
  ciudad: '',
  departamento: '',
  fecha_apertura: '',
  tipo_sucursal: '',
  estado: true
})

watch(
  () => props.sucursal,
  (valor) => {
    Object.assign(formulario, {
      nombre: valor?.nombre ?? '',
      direccion: valor?.direccion ?? '',
      telefono: valor?.telefono ?? '',
      correo: valor?.correo ?? '',
      ciudad: valor?.ciudad ?? '',
      departamento: valor?.departamento ?? '',
      fecha_apertura:
        typeof valor?.fecha_apertura === 'string'
          ? valor.fecha_apertura.substring(0, 10)
          : '',
      tipo_sucursal: valor?.tipo_sucursal ?? '',
      estado: valor?.estado ?? true
    })
  },
  { immediate: true }
)

const enviar = () => emit('guardar', { ...formulario })
</script>

<template>
  <form class="form-card" @submit.prevent="enviar">
    <div class="form-title-row">
      <div>
        <p class="eyebrow">{{ sucursal ? 'Edición' : 'Registro' }}</p>
        <h2>{{ sucursal ? 'Editar Sucursal' : 'Nueva Sucursal' }}</h2>
      </div>
    </div>

    <fieldset class="form-grid" :disabled="guardando">
      <label>
        Nombre
        <input v-model.trim="formulario.nombre" maxlength="150" required />
      </label>

      <label>
        Teléfono
        <input v-model.trim="formulario.telefono" maxlength="20" required />
      </label>

      <label>
        Correo
        <input
          v-model.trim="formulario.correo"
          type="email"
          maxlength="150"
          required
        />
      </label>

      <label>
        Ciudad
        <input v-model.trim="formulario.ciudad" maxlength="100" required />
      </label>

      <label>
        Departamento
        <input
          v-model.trim="formulario.departamento"
          maxlength="100"
          required
        />
      </label>

      <label>
        Fecha apertura
        <input v-model="formulario.fecha_apertura" type="date" required />
      </label>

      <label>
        Tipo
        <select v-model="formulario.tipo_sucursal" required>
          <option value="">Seleccione</option>
          <option v-if="formulario.tipo_sucursal && !['Principal', 'Secundaria'].includes(formulario.tipo_sucursal)" :value="formulario.tipo_sucursal">{{ formulario.tipo_sucursal }}</option>
          <option value="Principal">Principal</option>
          <option value="Secundaria">Secundaria</option>
        </select>
      </label>

      <label>
        Estado
        <select v-model="formulario.estado">
          <option :value="true">Activa</option>
          <option :value="false">Inactiva</option>
        </select>
      </label>

      <label class="full">
        Dirección
        <textarea
          v-model.trim="formulario.direccion"
          maxlength="250"
          required
        />
      </label>
    </fieldset>

    <div class="form-actions">
      <button
        type="button"
        class="secondary"
        :disabled="guardando"
        @click="$emit('cancelar')"
      >
        Cancelar
      </button>
      <button type="submit" :disabled="guardando">
        {{ guardando ? 'Guardando...' : 'Guardar' }}
      </button>
    </div>
  </form>
</template>
