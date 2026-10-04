<script setup>
import { obtenerId } from '../../services/sucursalesService'
defineProps({
  ocupado: { type: Boolean, default: false },
  sucursales: {
    type: Array,
    required: true
  },
  procesandoId: {
    type: String,
    default: ''
  }
})

defineEmits(['editar', 'eliminar'])
</script>

<template>
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Ciudad</th>
          <th>Departamento</th>
          <th>Teléfono</th>
          <th>Tipo</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="sucursal in sucursales" :key="obtenerId(sucursal)">
          <td>
            <strong>{{ sucursal.nombre }}</strong>
            <small>{{ sucursal.correo }}</small>
          </td>
          <td>{{ sucursal.ciudad }}</td>
          <td>{{ sucursal.departamento }}</td>
          <td>{{ sucursal.telefono }}</td>
          <td>{{ sucursal.tipo_sucursal }}</td>
          <td>
            <span
              class="status"
              :class="sucursal.estado ? 'active' : 'inactive'"
            >
              {{ sucursal.estado ? 'Activa' : 'Inactiva' }}
            </span>
          </td>
          <td class="actions">
            <button class="edit" :disabled="ocupado || !obtenerId(sucursal)" @click="$emit('editar', sucursal)">
              Editar
            </button>
            <button
              class="danger"
              :disabled="ocupado || !obtenerId(sucursal)"
              @click="$emit('eliminar', sucursal)"
            >
              {{ procesandoId === obtenerId(sucursal) ? 'Eliminando...' : 'Eliminar' }}
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
