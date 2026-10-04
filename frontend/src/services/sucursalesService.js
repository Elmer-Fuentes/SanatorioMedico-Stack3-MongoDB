import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  },
  timeout: 10000
})

export const obtenerId = (sucursal) => sucursal?.id ?? sucursal?._id ?? ''

const validarId = (id) => {
  if (typeof id !== 'string' || !/^[a-f0-9]{24}$/i.test(id)) {
    throw new Error('El identificador de la sucursal no es válido.')
  }
  return encodeURIComponent(id)
}

export const obtenerSucursales = async (buscar = '') => {
  const respuesta = (await api.get('/sucursales', { params: { buscar } })).data
  return respuesta.datos ?? respuesta
}

export const obtenerSucursal = async (id) =>
  (await api.get(`/sucursales/${validarId(id)}`)).data

export const agregarSucursal = async (datos) =>
  (await api.post('/sucursales', datos)).data

export const editarSucursal = async (id, datos) =>
  (await api.put(`/sucursales/${validarId(id)}`, datos)).data

export const eliminarSucursal = async (id) =>
  (await api.delete(`/sucursales/${validarId(id)}`)).data
