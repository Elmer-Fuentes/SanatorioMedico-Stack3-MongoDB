<script setup>
import { computed, onMounted, onBeforeUnmount, ref, watch } from 'vue'
import SucursalesTable from '../components/sucursales/SucursalesTable.vue'
import SucursalForm from '../components/sucursales/SucursalForm.vue'
import {
  obtenerSucursales,
  obtenerId,
  agregarSucursal,
  editarSucursal,
  eliminarSucursal
} from '../services/sucursalesService'

const sucursales = ref([])
const mostrarFormulario = ref(false)
const seleccionada = ref(null)
const mensaje = ref('')
const error = ref('')
const cargando = ref(true)
const guardando = ref(false)
const procesandoId = ref('')
const buscar = ref('')
const ocupado = computed(() => cargando.value || guardando.value || !!procesandoId.value)
let consulta = 0
let temporizador

const limpiarMensajes = () => {
  mensaje.value = ''
  error.value = ''
}

const extraerError = (e, alternativo) => {
  const errores = e?.response?.data?.errors
  if (errores && typeof errores === 'object') {
    return Object.values(errores).flat().join(' ')
  }

  return e?.response?.data?.mensaje || (e?.response ? alternativo : e?.message || alternativo)
}

const cargar = async () => {
  const numero = ++consulta
  try {
    cargando.value = true
    error.value = ''
    const datos = await obtenerSucursales(buscar.value)
    if (numero === consulta) sucursales.value = datos
  } catch (e) {
    if (numero === consulta) error.value = extraerError(e, 'No fue posible consultar las sucursales.')
  } finally {
    if (numero === consulta) cargando.value = false
  }
}

const nueva = () => {
  if (ocupado.value) return
  limpiarMensajes()
  seleccionada.value = null
  mostrarFormulario.value = true
}

const editar = (sucursal) => {
  if (ocupado.value) return
  limpiarMensajes()
  if (!obtenerId(sucursal)) {
    error.value = 'La sucursal no tiene un identificador válido.'
    return
  }
  seleccionada.value = { ...sucursal }
  mostrarFormulario.value = true
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const cancelar = () => {
  mostrarFormulario.value = false
  seleccionada.value = null
}

const guardar = async (datos) => {
  if (ocupado.value) return
  try {
    guardando.value = true
    limpiarMensajes()

    if (seleccionada.value) {
      await editarSucursal(obtenerId(seleccionada.value), datos)
      mensaje.value = 'Sucursal actualizada correctamente.'
    } else {
      await agregarSucursal(datos)
      mensaje.value = 'Sucursal creada correctamente.'
    }

    cancelar()
    await cargar()
  } catch (e) {
    error.value = extraerError(e, 'No fue posible guardar la sucursal.')
  } finally {
    guardando.value = false
  }
}

const eliminar = async (sucursal) => {
  if (ocupado.value) return
  limpiarMensajes()
  const id = obtenerId(sucursal)
  if (!id) {
    error.value = 'La sucursal no tiene un identificador válido.'
    return
  }
  if (!confirm(`¿Eliminar la sucursal ${sucursal.nombre}?`)) return

  try {
    procesandoId.value = id
    limpiarMensajes()
    await eliminarSucursal(id)
    mensaje.value = 'Sucursal eliminada correctamente.'
    await cargar()
  } catch (e) {
    error.value = extraerError(e, 'No fue posible eliminar la sucursal.')
  } finally {
    procesandoId.value = ''
  }
}

onMounted(cargar)
watch(buscar, () => {
  limpiarMensajes()
  ++consulta
  cargando.value = true
  clearTimeout(temporizador)
  temporizador = setTimeout(cargar, 300)
})
onBeforeUnmount(() => { clearTimeout(temporizador); ++consulta })
</script>

<template>
  <main class="page">
    <div class="page-header">
      <div>
        <p class="eyebrow">Sanatorio Médico</p>
        <h1>Sucursales</h1>
        <p>Administración de sucursales mediante Laravel, MongoDB y Vue.</p>
      </div>

      <button :disabled="ocupado" @click="nueva">+ Nueva Sucursal</button>
    </div>

    <p v-if="mensaje" class="success" role="status">{{ mensaje }}</p>
    <p v-if="error" class="error" role="alert">{{ error }}</p>

    <SucursalForm
      v-if="mostrarFormulario"
      :sucursal="seleccionada"
      :guardando="ocupado"
      @guardar="guardar"
      @cancelar="cancelar"
    />

    <label class="search-box">
      Buscar sucursales
      <input v-model.trim="buscar" type="search" maxlength="150" :disabled="guardando || !!procesandoId" placeholder="Nombre, ciudad, departamento, teléfono, correo o tipo" />
    </label>

    <div v-if="cargando" class="empty-state" role="status">
      Cargando sucursales...
    </div>

    <div v-else-if="sucursales.length === 0" class="empty-state">
      {{ buscar ? 'No se encontraron sucursales para esta búsqueda.' : 'No existen sucursales registradas.' }}
    </div>

    <SucursalesTable
      v-else
      :sucursales="sucursales"
      :procesando-id="procesandoId"
      :ocupado="ocupado"
      @editar="editar"
      @eliminar="eliminar"
    />
  </main>
</template>
