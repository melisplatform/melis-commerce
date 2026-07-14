import { useEffect, useState } from 'react'
import { card, inputCss, btnGhost, iconBtn, label } from './styles'
import { PlusIcon, TrashIcon } from './icons'
import { ConfirmModal } from './ConfirmModal'
import { type Address, type AddressOptions, newAddress } from './address'
import type { T } from './i18n'

/** Formulaire d'adresse (réutilisé : modale d'ajout + panneau d'édition de droite). */
function AddressForm({ value, onChange, opts, t }: { value: Address; onChange: (a: Address) => void; opts: AddressOptions; t: T }) {
  const set = <K extends keyof Address>(k: K, v: Address[K]) => onChange({ ...value, [k]: v })
  const field = (k: keyof Address, lbl: string) => (
    <div><label style={label}>{lbl}</label><input style={inputCss} value={String(value[k] ?? '')} onChange={(e) => set(k, e.target.value as never)} autoComplete="off" /></div>
  )
  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
      <div><label style={label}>{t('ad_name')}<span style={{ color: '#ef4444', marginLeft: 2 }}>*</span></label><input style={inputCss} value={value.addressName} onChange={(e) => set('addressName', e.target.value)} autoComplete="off" /></div>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
        <div><label style={label}>{t('ad_type')}<span style={{ color: '#ef4444', marginLeft: 2 }}>*</span></label>
          <select style={inputCss} value={value.type} onChange={(e) => set('type', Number(e.target.value))}>
            {opts.types.map((o) => <option key={o.id} value={o.id}>{o.name}</option>)}
          </select>
        </div>
        <div><label style={label}>{t('ad_civility')}</label>
          <select style={inputCss} value={value.civility} onChange={(e) => set('civility', Number(e.target.value))}>
            <option value={0}>{t('ad_civ_ph')}</option>
            {opts.civilities.map((o) => <option key={o.id} value={o.id}>{o.name}</option>)}
          </select>
        </div>
      </div>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: 12 }}>{field('firstname', t('ad_firstname'))}{field('name', t('ad_lastname'))}{field('middleName', t('ad_middlename'))}</div>
      <div style={{ display: 'grid', gridTemplateColumns: '120px 1fr', gap: 12 }}>{field('num', t('ad_num'))}{field('street', t('ad_street'))}</div>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>{field('building', t('ad_building'))}{field('zipcode', t('ad_zip'))}</div>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>{field('city', t('ad_city'))}{field('state', t('ad_state'))}</div>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>{field('country', t('ad_country'))}{field('phoneMobile', t('ad_phone'))}</div>
    </div>
  )
}

/**
 * Éditeur d'adresses master-détail STAGED (comptes & contacts) : liste de chips à gauche,
 * formulaire d'édition à droite, bouton « Ajouter » → modale. Rien n'est persisté ici — le
 * parent remonte `addresses` et persiste via SON bouton Save (sauvegarde groupée).
 */
export function AddressEditor({ addresses, onChange, options, t }: {
  addresses: Address[]; onChange: (a: Address[]) => void; options: AddressOptions; t: T
}) {
  const [selId, setSelId] = useState<number | null>(null)
  const [modal, setModal] = useState<Address | null>(null)
  const [modalErr, setModalErr] = useState<string | null>(null)
  const [toDelete, setToDelete] = useState<Address | null>(null)
  useEffect(() => { if (selId === null && addresses.length) setSelId(addresses[0].id) }, [addresses, selId])

  const selected = addresses.find((a) => a.id === selId) ?? null
  const defType = options.types[0]?.id ?? 1
  const updateSel = (a: Address) => onChange(addresses.map((x) => (x.id === selId ? a : x)))
  function saveModal() {
    if (!modal) return
    if (!modal.addressName.trim()) { setModalErr(t('ad_err_name')); return }
    onChange([...addresses, modal]); setSelId(modal.id); setModal(null); setModalErr(null)
  }
  function confirmDelete() {
    if (!toDelete) return
    const id = toDelete.id
    const rest = addresses.filter((a) => a.id !== id)
    onChange(rest)
    if (selId === id) setSelId(rest[0]?.id ?? null)
    setToDelete(null)
  }

  return (
    <div>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16, marginBottom: 16 }}>
        <h3 style={{ fontSize: 16, fontWeight: 600, margin: 0 }}>{t('ad_section')}</h3>
        <button style={{ ...btnGhost, height: 34, color: 'var(--color-primary)', borderColor: 'var(--color-primary)' }} onClick={() => { setModal(newAddress(defType)); setModalErr(null) }}><PlusIcon />{t('ad_add')}</button>
      </div>
      {addresses.length === 0 ? (
        <div style={{ borderRadius: 8, background: 'var(--color-muted,rgba(0,0,0,.05))', color: 'var(--color-muted-foreground)', padding: '12px 16px', fontSize: 14 }}>{t('ad_empty')}</div>
      ) : (
        <div style={{ display: 'grid', gridTemplateColumns: '220px 1fr', gap: 20, alignItems: 'start' }}>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
            {addresses.map((a) => {
              const sel = a.id === selId
              return (
                <div key={a.id} onClick={() => setSelId(a.id)} style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8, cursor: 'pointer', borderRadius: 8, padding: '8px 12px', fontSize: 14, fontWeight: 500, background: sel ? 'var(--color-primary)' : 'var(--color-muted,rgba(0,0,0,.04))', color: sel ? 'var(--color-primary-foreground,#fff)' : 'var(--color-foreground)' }}>
                  <span style={{ overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{a.addressName || t('ad_new_title')}</span>
                  <button onClick={(e) => { e.stopPropagation(); setToDelete(a) }} title={t('del')} style={{ ...iconBtn, width: 20, height: 20, color: 'var(--color-destructive,#ef4444)' }}><TrashIcon /></button>
                </div>
              )
            })}
          </div>
          <div style={{ ...card, padding: 20 }}>{selected && <AddressForm value={selected} onChange={updateSel} opts={options} t={t} />}</div>
        </div>
      )}
      {modal && (
        <div onClick={(e) => { if (e.target === e.currentTarget) setModal(null) }} style={{ position: 'fixed', inset: 0, zIndex: 60, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)', padding: 16 }}>
          <div style={{ ...card, width: '100%', maxWidth: 640, maxHeight: '90vh', overflow: 'auto' }}>
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '16px 20px', borderBottom: '1px solid var(--color-border)' }}>
              <h3 style={{ fontSize: 16, fontWeight: 600, margin: 0 }}>{t('ad_new_title')}</h3>
              <button style={{ ...iconBtn, width: 24, height: 24 }} onClick={() => setModal(null)}>✕</button>
            </div>
            <div style={{ padding: 20 }}>
              {modalErr && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14, marginBottom: 14 }}>{modalErr}</div>}
              <AddressForm value={modal} onChange={(a) => { setModal(a); setModalErr(null) }} opts={options} t={t} />
            </div>
            <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, padding: '12px 20px', borderTop: '1px solid var(--color-border)' }}>
              <button style={btnGhost} onClick={() => setModal(null)}>{t('cancel')}</button>
              <button style={{ ...btnGhost, color: 'var(--color-primary)', borderColor: 'var(--color-primary)' }} onClick={saveModal}>{t('save')}</button>
            </div>
          </div>
        </div>
      )}
      {toDelete && (
        <ConfirmModal title={t('ad_del_title')} message={t('ad_del_confirm', { u: toDelete.addressName || t('ad_new_title') })} confirmLabel={t('del')} cancelLabel={t('cancel')} onConfirm={confirmDelete} onClose={() => setToDelete(null)} />
      )}
    </div>
  )
}
