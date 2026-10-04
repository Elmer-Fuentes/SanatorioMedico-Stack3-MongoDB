import assert from 'node:assert/strict'
import { readFile } from 'node:fs/promises'

// Aislar Axios permite verificar que un ID inválido no origina ninguna petición.
const peticiones = []
globalThis.__axiosSucursalesPrueba = {
  create: () => Object.fromEntries(['get', 'post', 'put', 'delete'].map(metodo => [metodo,
    async (...args) => {
      peticiones.push({ metodo, args })
      return { data: { ok: true, datos: [] } }
    }
  ]))
}
const codigo = (await readFile(new URL('../src/services/sucursalesService.js', import.meta.url), 'utf8'))
  .replace("import axios from 'axios'", 'const axios = globalThis.__axiosSucursalesPrueba')
  .replaceAll('import.meta.env.VITE_API_URL', "'http://localhost:8000/api'")
const service = await import(`data:text/javascript;base64,${Buffer.from(codigo).toString('base64')}`)
const id = '1234567890abcdef12345678'
assert.equal(service.obtenerId({ id, _id: 'otro' }), id)
assert.equal(service.obtenerId({ _id: id }), id)
assert.equal(service.obtenerId(null), '')
for (const invalido of [undefined, null, '', 'undefined', 'invalido', {}]) {
  await assert.rejects(service.obtenerSucursal(invalido))
  await assert.rejects(service.editarSucursal(invalido, {}))
  await assert.rejects(service.eliminarSucursal(invalido))
}
assert.equal(peticiones.length, 0)
assert.deepEqual(await service.obtenerSucursales('Guatemala'), [])
assert.deepEqual(peticiones[0].args, ['/sucursales', { params: { buscar: 'Guatemala' } }])
await service.obtenerSucursal(id)
await service.editarSucursal(id, { nombre: 'Editada' })
await service.eliminarSucursal(id)
assert.ok(peticiones.slice(1).every(p => p.args[0] === `/sucursales/${id}`))
delete globalThis.__axiosSucursalesPrueba
console.log('OK: id/_id, bloqueo de IDs inválidos, búsqueda y rutas Axios de consulta/edición/eliminación.')
