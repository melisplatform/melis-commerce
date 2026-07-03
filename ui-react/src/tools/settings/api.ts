import { apiFetch } from '../../shared/api'

export interface StockAlertRecipient { id: number; email: string; userId: number | null; userName: string | null }

export type AccountType = 'manual_input' | 'company_name' | 'contact_name'

export interface SettingsData {
  stockLevelAlert: number | null
  recipients: StockAlertRecipient[]
  accountType: AccountType
}

export interface UserOption { id: number; name: string; email: string }
export interface SettingsOptions { users: UserOption[] }

export const fetchSettings        = () => apiFetch<SettingsData>('/melis/react-api/settings')
export const fetchSettingsOptions = () => apiFetch<SettingsOptions>('/melis/react-api/settings/options')

export const saveSettings = (payload: {
  stockLevelAlert: number | null
  recipients: { id?: number; email: string; userId?: number | null }[]
  accountType: AccountType
}) =>
  apiFetch<null>('/melis/react-api/settings/save', {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload),
  })
