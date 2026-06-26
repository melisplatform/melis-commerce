import type { Option } from './api'

/** Adresse MelisCommerce (table melis_ecom_client_address) — partagée comptes & contacts. */
export interface Address {
  id: number; addressName: string; type: number; typeName?: string; civility: number
  firstname: string; name: string; middleName: string; num: string; street: string; building: string
  zipcode: string; city: string; state: string; country: string; phoneMobile: string
}
export interface AddressOptions { types: Option[]; civilities: Option[] }

// Id temporaire (négatif) pour une adresse non encore enregistrée → le backend l'INSÈRE (id<=0).
let _tmp = 0
export const newAddress = (typeDefault: number): Address => ({
  id: --_tmp, addressName: '', type: typeDefault, civility: 0,
  firstname: '', name: '', middleName: '', num: '', street: '', building: '',
  zipcode: '', city: '', state: '', country: '', phoneMobile: '',
})
