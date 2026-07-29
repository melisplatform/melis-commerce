import { useEffect, useState } from 'react'
import { fetchSettings, fetchSettingsOptions, saveSettings, type AccountType, type StockAlertRecipient, type UserOption } from './api'
import { DICT } from './dict'
import { makeT } from '../../shared/i18n'
import { card, inputCss, label, btnGhost, btnPrimary, iconBtn } from '../../shared/styles'
import { PlusIcon, TrashIcon, GlobeIcon, UsersIcon, KeyIcon } from '../../shared/icons'
import { ViewModeToggle, LegacyFrame } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { useCaps } from '../../shared/useCaps'
import { Tabs } from '../../shared/Tabs'
import { SearchableSelect } from '../../shared/SearchableSelect'
import { useIsNarrow } from '../../shared/useIsNarrow'

const TOOL_MELIS_KEY = 'meliscommerce_settings_page'

const labelRow = { display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8 } as const
const fieldSectionTitle = { display: 'flex', alignItems: 'center', gap: 8, fontSize: 15, fontWeight: 500, color: 'var(--color-muted-foreground)', margin: '0 0 20px' } as const
const fieldGap = { display: 'flex', flexDirection: 'column', gap: 16 } as const

function InfoDot({ text }: { text: string }) {
  if (!text) return null
  return (
    <span title={text} style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 15, height: 15, borderRadius: 999, background: 'var(--color-muted-foreground)', color: 'var(--color-background,#fff)', fontSize: 10, fontWeight: 700, cursor: 'help', flexShrink: 0 }}>i</span>
  )
}

function GearIcon() {
  return <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ width: 16, height: 16, flexShrink: 0 }}><circle cx="12" cy="12" r="3" /><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" /></svg>
}

export default function SettingsPage() {
  const t = makeT(DICT)
  const narrow = useIsNarrow()
  const { can, loaded: capsLoaded } = useCaps(TOOL_MELIS_KEY)
  const [mode, setMode] = useState<'react' | 'old'>('react')
  const [oldLoaded, setOldLoaded] = useState(false)
  const [loading, setLoading] = useState(true)
  const [saving, setSaving] = useState(false)
  const [activeTab, setActiveTab] = useState('main')

  const [users, setUsers] = useState<UserOption[]>([])
  const [stockLevelAlert, setStockLevelAlert] = useState('')
  const [recipients, setRecipients] = useState<StockAlertRecipient[]>([])
  const [pickUserId, setPickUserId] = useState<number>(0)

  const [accountType, setAccountType] = useState<AccountType>('manual_input')
  const [accountsLocked, setAccountsLocked] = useState(true)

  useEffect(() => {
    setLoading(true)
    Promise.all([fetchSettings(), fetchSettingsOptions()])
      .then(([s, o]) => {
        setStockLevelAlert(s.stockLevelAlert !== null ? String(s.stockLevelAlert) : '')
        setRecipients(s.recipients)
        setAccountType(s.accountType)
        setUsers(o.users)
      })
      .catch(() => null)
      .finally(() => setLoading(false))
  }, [])

  const TABS = [
    { key: 'main',     label: t('tab_main'),     icon: <GearIcon /> },
    { key: 'accounts', label: t('tab_accounts'), icon: <UsersIcon /> },
  ].filter((tb) => can(tb.key))

  useEffect(() => {
    if (capsLoaded && TABS.length && !TABS.some((tb) => tb.key === activeTab)) setActiveTab(TABS[0].key)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [capsLoaded])

  const availableUsers = users.filter((u) => !recipients.some((r) => r.userId === u.id))

  function addRecipient() {
    const u = users.find((x) => x.id === pickUserId)
    if (!u) return
    setRecipients((p) => [...p, { id: 0, email: u.email, userId: u.id, userName: u.name }])
    setPickUserId(0)
  }

  function removeRecipient(idx: number) {
    setRecipients((p) => p.filter((_, i) => i !== idx))
  }

  async function submit() {
    for (const r of recipients) {
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(r.email)) { notify('ko', t('title'), t('err_email_invalid', { u: r.email })); return }
    }
    setSaving(true)
    try {
      await saveSettings({
        stockLevelAlert: stockLevelAlert.trim() === '' ? null : Number(stockLevelAlert),
        recipients: recipients.map((r) => ({ id: r.id || undefined, email: r.email, userId: r.userId })),
        accountType,
      })
      notify('ok', t('title'), t('saved'))
    } catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : 'Error') }
    finally { setSaving(false) }
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, boxSizing: 'border-box', ...(mode === 'old' ? { height: '100%', overflow: 'hidden' } : {}) }}>
      <div style={{ display: 'flex', alignItems: narrow ? 'flex-start' : 'center', justifyContent: 'space-between', gap: 16, flexWrap: narrow ? 'wrap' : 'nowrap' }}>
        <div>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{t('title')}</h1>
          <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', margin: '2px 0 0' }}>{t('subtitle')}</p>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center', flexBasis: narrow ? '100%' : undefined }}>
          <ViewModeToggle mode={mode} onReact={() => setMode('react')} onOld={() => { setMode('old'); setOldLoaded(true) }} />
          {can('edit') && <button style={{ ...btnPrimary, flex: narrow ? 1 : undefined, justifyContent: narrow ? 'center' : undefined }} onClick={submit} disabled={saving || loading}>{saving ? '…' : t('save')}</button>}
        </div>
      </div>

      {oldLoaded && <LegacyFrame melisKey={TOOL_MELIS_KEY} title={t('title')} visible={mode === 'old'} />}

      <div style={{ display: mode === 'react' ? 'flex' : 'none', flexDirection: 'column', gap: 20, flex: 1, minHeight: 0 }}>
        {loading ? (
          <div style={{ padding: 40, display: 'flex', justifyContent: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
        ) : (
          <>
            <Tabs tabs={TABS} active={activeTab} onChange={setActiveTab} />

            {activeTab === 'main' && (
              // Même design que l'onglet Propriétés produit (carte Alerte) : niveau + destinataires
              // (comptes BO uniquement, sélecteur recherchable par email + pastilles).
              <div style={{ maxWidth: 680 }}>
                <div style={{ ...card, padding: 20 }}>
                  <h3 style={fieldSectionTitle}><GearIcon />{t('section_stock_alert')}</h3>
                  <div style={{ marginBottom: 20 }}>
                    <div style={labelRow}><label style={label}>{t('field_stock_level')}</label><InfoDot text={t('tip_stock_level')} /></div>
                    <input style={inputCss} type="number" min={0} value={stockLevelAlert} onChange={(e) => setStockLevelAlert(e.target.value)} />
                  </div>

                  <h4 style={{ fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 10px' }}>{t('section_recipients')}</h4>
                  <div style={{ display: 'flex', gap: 8, marginBottom: 12 }}>
                    <SearchableSelect options={availableUsers.map((u) => ({ id: u.id, name: u.name ? `${u.name} (${u.email})` : u.email }))} value={pickUserId} onChange={setPickUserId}
                      placeholder={t('pick_user')} searchPlaceholder={t('recip_search_ph')} noResult={t('recip_no_result')} />
                    <button style={btnGhost} onClick={addRecipient} disabled={!pickUserId}><PlusIcon />{t('recipients_add')}</button>
                  </div>
                  {recipients.length === 0 ? (
                    <p style={{ fontSize: 13, color: 'var(--color-muted-foreground)', margin: 0 }}>{t('recipients_empty')}</p>
                  ) : (
                    <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8 }}>
                      {recipients.map((r, idx) => (
                        <span key={idx} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '5px 10px', borderRadius: 999, background: 'var(--color-muted,rgba(0,0,0,.05))', fontSize: 14 }}>
                          {r.userName ? `${r.userName} (${r.email})` : r.email}
                          <button style={{ ...iconBtn, width: 18, height: 18, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => removeRecipient(idx)}><TrashIcon /></button>
                        </span>
                      ))}
                    </div>
                  )}
                </div>
              </div>
            )}

            {activeTab === 'accounts' && (
              <div style={{ ...card, padding: 24, maxWidth: 480 }}>
                <h3 style={fieldSectionTitle}><GlobeIcon />{t('section_account_name')}</h3>
                <div style={fieldGap}>
                  <label style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 13, cursor: 'pointer' }}>
                    <input type="checkbox" checked={!accountsLocked} onChange={(e) => setAccountsLocked(!e.target.checked)} />
                    <KeyIcon />{t('lock_checkbox')}
                  </label>
                  <p style={{ fontSize: 12, color: '#dc2626', margin: 0 }}>{t('lock_warning')}</p>
                  <div>
                    <label style={label}>{t('field_account_type')}</label>
                    <select style={{ ...inputCss, opacity: accountsLocked ? 0.6 : 1, cursor: accountsLocked ? 'not-allowed' : 'pointer' }}
                      value={accountType} disabled={accountsLocked}
                      onChange={(e) => setAccountType(e.target.value as AccountType)}>
                      <option value="manual_input">{t('account_type_manual_input')}</option>
                      <option value="company_name">{t('account_type_company_name')}</option>
                      <option value="contact_name">{t('account_type_contact_name')}</option>
                    </select>
                  </div>
                </div>
              </div>
            )}
          </>
        )}
      </div>
    </div>
  )
}
