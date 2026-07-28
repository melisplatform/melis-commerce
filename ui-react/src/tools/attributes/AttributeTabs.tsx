import { useEffect, useState } from 'react'
import {
  fetchAttributeValues, saveAttributeValue, deleteAttributeValue, attributeValueBinaryUrl,
  type AttributeValueItem, type AttributeValueRaw, type LangOption,
} from './api'
import { card, inputCss, label, btnGhost, btnPrimary, iconBtn } from '../../shared/styles'
import { PlusIcon, GripIcon, ChevronDownIcon, PencilIcon, TrashIcon } from '../../shared/icons'
import { ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { SimpleTable, type SimpleCol } from '../../shared/SimpleTable'
import { ColManager } from '../../shared/ColManager'
import { makeColStore, visibleCols } from '../../shared/columns'
import { type T } from '../../shared/i18n'
import { DatePicker } from '../../shared/DatePicker'

const fieldGap = { display: 'flex', flexDirection: 'column', gap: 16 } as const
const labelRow = { display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8 } as const

function fileToBase64(file: File): Promise<string> {
  return new Promise((resolve, reject) => {
    const reader = new FileReader()
    reader.onload = () => {
      const result = String(reader.result ?? '')
      const comma = result.indexOf(',')
      resolve(comma >= 0 ? result.slice(comma + 1) : result)
    }
    reader.onerror = () => reject(reader.error)
    reader.readAsDataURL(file)
  })
}

function displayValue(v: AttributeValueRaw, typeColumn: string, t: T): string {
  if (v === null || v === undefined || v === '') return '—'
  if (typeColumn === 'bool') return v ? t('yes') : t('no')
  return String(v)
}

const VALUE_COL_ORDER = ['id', 'value'] as const
const valueColStore = makeColStore('melis-attr-values-cols-v1', VALUE_COL_ORDER)

// ── Onglet Values : liste des valeurs de l'attribut + éditeur (créer/modifier) ──
export function AttributeValuesTab({ attributeId, languages, can, t }: {
  attributeId: number; languages: LangOption[]; can?: (cap: string) => boolean; t: T
}) {
  const allow = (cap: string) => (can ? can(cap) : true)

  const [items, setItems] = useState<AttributeValueItem[]>([])
  const [typeColumn, setTypeColumn] = useState('varchar')
  const [loading, setLoading] = useState(false)
  const [tick, setTick] = useState(0)
  const [editing, setEditing] = useState<AttributeValueItem | 'new' | null>(null)
  const [toDelete, setToDelete] = useState<AttributeValueItem | null>(null)
  const [colDefs, setColDefs] = useState(valueColStore.load)
  const [showCols, setShowCols] = useState(false)

  useEffect(() => {
    setLoading(true)
    fetchAttributeValues(attributeId)
      .then((r) => { setItems(r.items); setTypeColumn(r.typeColumn) })
      .catch(() => null)
      .finally(() => setLoading(false))
  }, [attributeId, tick])

  async function confirmDelete() {
    if (!toDelete) return
    try { await deleteAttributeValue(attributeId, toDelete.id); notify('ok', t('tab_values'), t('deleted')); setToDelete(null); setTick((x) => x + 1) }
    catch (e) { notify('ko', t('tab_values'), e instanceof Error ? e.message : 'Error'); setToDelete(null) }
  }

  const allCols: Record<string, SimpleCol<AttributeValueItem>> = {
    id: { key: 'id', label: t('value_col_id'), width: 70, render: (r) => <span style={{ color: 'var(--color-muted-foreground)' }}>{r.id}</span> },
    value: { key: 'value', label: t('value_col_value'), render: (r) => displayValue(r.displayValue, typeColumn, t) },
  }
  const action: SimpleCol<AttributeValueItem> = {
    key: 'action', label: t('col_action'), width: 90, render: (r) => (
      <div style={{ display: 'inline-flex', gap: 6 }}>
        {allow('values.edit') && <button onClick={() => setEditing(r)}
          style={iconBtn} title={t('edit')}><PencilIcon /></button>}
        {allow('values.delete') && <button onClick={() => setToDelete(r)}
          style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')}><TrashIcon /></button>}
      </div>
    ),
  }
  const cols: SimpleCol<AttributeValueItem>[] = [...visibleCols(colDefs).map((c) => allCols[c.id]).filter(Boolean), action]

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
      <SimpleTable cols={cols} rows={items} rowKey={(r) => r.id} empty={t('values_empty')} loading={loading} loadingText={t('loading')} t={t}
        search={(r) => `${r.reference ?? ''} ${displayValue(r.displayValue, typeColumn, t)}`} searchPlaceholder={t('search')}
        paginate={false} countLabel={(n) => t('values_count', { n })}
        toolbarEnd={(
          <div style={{ position: 'relative' }}>
            <button style={{ ...iconBtn, width: 'auto', height: 34, padding: '0 12px', gap: 6, border: '1px solid var(--color-border)' }} title={t('columns')} onClick={() => setShowCols((v) => !v)}><GripIcon /><span>{t('columns')}</span></button>
            {showCols && (
              <ColManager cols={colDefs} labelFor={(id) => t(`value_col_${id}`)} onChange={setColDefs} onClose={() => setShowCols(false)}
                save={valueColStore.save} defaults={valueColStore.DEFAULT} t={t} />
            )}
          </div>
        )}
        onAdd={allow('values.create') ? () => setEditing('new') : undefined} addIcon={<PlusIcon />} addTitle={t('values_add')} />

      {editing && (
        <AttributeValueEditor
          attributeId={attributeId} value={editing === 'new' ? null : editing} typeColumn={typeColumn} languages={languages} t={t}
          onClose={() => setEditing(null)}
          onSaved={() => { setEditing(null); setTick((x) => x + 1) }}
        />
      )}

      {toDelete && (
        <ConfirmModal title={t('value_del_title')} message={t('value_del_confirm')}
          onConfirm={confirmDelete} onCancel={() => setToDelete(null)} t={t} />
      )}
    </div>
  )
}

// ── Éditeur de valeur (modale) — un champ par langue, type déterminé par l'attribut ──
function AttributeValueEditor({ attributeId, value, typeColumn, languages, t, onClose, onSaved }: {
  attributeId: number; value: AttributeValueItem | null; typeColumn: string; languages: LangOption[]; t: T
  onClose: () => void; onSaved: () => void
}) {
  const isEdit = !!value
  const [perLangOpen, setPerLangOpen] = useState(typeColumn === 'binary')
  const [perLang, setPerLang] = useState<Record<number, AttributeValueRaw>>(() => {
    const init: Record<number, AttributeValueRaw> = {}
    for (const l of languages) {
      const existing = value?.translations.find((tr) => tr.langId === l.id)
      // Booléen = saisi comme un champ texte 0/1 (comme le legacy), stocké en string dans l'état.
      init[l.id] = existing
        ? (typeColumn === 'bool' ? (existing.value ? '1' : '0') : existing.value)
        : (typeColumn === 'bool' ? '' : null)
    }
    return init
  })
  const [fillAll, setFillAll] = useState<string>('')
  const [saving, setSaving] = useState(false)

  const hasFileByLang: Record<number, boolean> = {}
  if (value) { for (const tr of value.translations) { hasFileByLang[tr.langId] = !!tr.hasFile } }

  function setLangValue(langId: number, v: AttributeValueRaw) {
    setPerLang((p) => ({ ...p, [langId]: v }))
  }

  async function onPickFile(langId: number, file: File | null) {
    if (!file) return
    const b64 = await fileToBase64(file)
    setLangValue(langId, b64)
  }

  async function submit() {
    setSaving(true)
    try {
      const translations: { langId: number; value: AttributeValueRaw }[] = []
      if (typeColumn === 'binary') {
        // On n'envoie QUE les langues où un nouveau fichier a été choisi dans cette session —
        // sinon on écraserait silencieusement un fichier déjà enregistré (l'API ne le recharge pas).
        for (const l of languages) {
          const v = perLang[l.id]
          if (typeof v === 'string' && v !== '') translations.push({ langId: l.id, value: v })
        }
      } else if (fillAll.trim() !== '') {
        // Le backend coerce en (int)(bool) pour le booléen : "1" → 1, "0"/"" → 0.
        for (const l of languages) translations.push({ langId: l.id, value: fillAll })
      } else {
        for (const l of languages) translations.push({ langId: l.id, value: perLang[l.id] ?? null })
      }

      await saveAttributeValue(attributeId, { id: value?.id, reference: value?.reference ?? '', translations })
      notify('ok', t('tab_values'), t('saved'))
      onSaved()
    } catch (e) { notify('ko', t('tab_values'), e instanceof Error ? e.message : 'Error') }
    finally { setSaving(false) }
  }

  function ValueInput({ langId }: { langId: number }) {
    const v = perLang[langId]
    if (typeColumn === 'bool') {
      // Champ texte 0/1 comme le legacy (pas de case à cocher).
      return <input style={inputCss} inputMode="numeric" placeholder="0 / 1" value={v === null || v === undefined ? '' : String(v)} onChange={(e) => setLangValue(langId, e.target.value)} />
    }
    if (typeColumn === 'int') {
      return <input style={inputCss} type="number" step="1" value={v === null || v === undefined ? '' : String(v)} onChange={(e) => setLangValue(langId, e.target.value === '' ? null : e.target.value)} />
    }
    if (typeColumn === 'float') {
      return <input style={inputCss} type="number" step="0.01" value={v === null || v === undefined ? '' : String(v)} onChange={(e) => setLangValue(langId, e.target.value === '' ? null : e.target.value)} />
    }
    if (typeColumn === 'datetime') {
      return <DatePicker t={t} value={v === null || v === undefined ? '' : String(v)} onChange={(nv) => setLangValue(langId, nv === '' ? null : nv)} />
    }
    if (typeColumn === 'text') {
      return <textarea style={{ ...inputCss, height: 70, resize: 'vertical', paddingTop: 8 }} value={v === null || v === undefined ? '' : String(v)} onChange={(e) => setLangValue(langId, e.target.value)} />
    }
    if (typeColumn === 'binary') {
      return (
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          {isEdit && hasFileByLang[langId] && typeof v !== 'string' && (
            <a href={attributeValueBinaryUrl(attributeId, value!.id, langId)} target="_blank" rel="noreferrer" style={{ fontSize: 12, color: 'var(--color-primary)' }}>
              {t('value_file_download')}
            </a>
          )}
          {!(isEdit && hasFileByLang[langId]) && typeof v !== 'string' && <span style={{ fontSize: 12, color: 'var(--color-muted-foreground)' }}>{t('value_file_none')}</span>}
          {typeof v === 'string' && v !== '' && <span style={{ fontSize: 12, color: '#16a34a' }}>✓</span>}
          <input type="file" onChange={(e) => onPickFile(langId, e.target.files?.[0] ?? null)} style={{ fontSize: 12 }} />
        </div>
      )
    }
    // varchar (défaut)
    return <input style={inputCss} value={v === null || v === undefined ? '' : String(v)} onChange={(e) => setLangValue(langId, e.target.value)} />
  }

  return (
    <div onClick={(e) => { if (e.target === e.currentTarget) onClose() }}
      style={{ position: 'fixed', inset: 0, zIndex: 60, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)', padding: 20 }}>
      <div style={{ ...card, padding: 24, width: '100%', maxWidth: 520, maxHeight: '85vh', overflow: 'auto' }}>
        <h3 style={{ fontSize: 16, fontWeight: 600, margin: '0 0 16px' }}>{isEdit ? t('value_edit_title') : t('value_new_title')}</h3>
        <div style={fieldGap}>
          {typeColumn !== 'binary' && (
            <div>
              <div style={labelRow}><label style={label}>{t(`type_${typeColumn}`)}</label></div>
              {typeColumn === 'bool' ? (
                <input style={inputCss} inputMode="numeric" placeholder="0 / 1" value={fillAll} onChange={(e) => setFillAll(e.target.value)} />
              ) : typeColumn === 'text' ? (
                <textarea style={{ ...inputCss, height: 70, resize: 'vertical', paddingTop: 8 }} value={fillAll} onChange={(e) => setFillAll(e.target.value)} />
              ) : (
                <input style={inputCss} type={typeColumn === 'int' || typeColumn === 'float' ? 'number' : typeColumn === 'datetime' ? 'date' : 'text'}
                  step={typeColumn === 'float' ? '0.01' : undefined}
                  value={fillAll} onChange={(e) => setFillAll(e.target.value)} />
              )}
            </div>
          )}

          <div style={{ border: '1px solid var(--color-border)', borderRadius: 8, overflow: 'hidden' }}>
            <button type="button" onClick={() => setPerLangOpen((v) => !v)}
              style={{ width: '100%', display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8, padding: '10px 12px', background: 'var(--color-muted)', border: 'none', cursor: 'pointer', color: 'var(--color-foreground)', fontSize: 13, fontWeight: 600 }}>
              <span>{t('value_field_perlang')}</span>
              <span style={{ display: 'inline-flex', transform: perLangOpen ? 'none' : 'rotate(-90deg)', transition: 'transform .1s' }}><ChevronDownIcon /></span>
            </button>
            {perLangOpen && (
              <div style={{ padding: 12, display: 'flex', flexDirection: 'column', gap: 12 }}>
                {languages.map((l) => (
                  <div key={l.id} style={{ opacity: typeColumn !== 'binary' && fillAll.trim() !== '' ? 0.4 : 1 }}>
                    <div style={labelRow}>
                      <label style={label}>{l.name}</label>
                      {l.flag ? <img src={`data:image/png;base64,${l.flag}`} alt="" style={{ width: 18, height: 18, borderRadius: 3 }} /> : null}
                    </div>
                    <div style={{ fontSize: 11, fontWeight: 600, color: 'var(--color-muted-foreground)', marginBottom: 4 }}>{t(`type_${typeColumn}`)}</div>
                    <ValueInput langId={l.id} />
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>
        <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 20 }}>
          <button style={btnGhost} onClick={onClose}>{t('cancel')}</button>
          <button style={btnPrimary} onClick={submit} disabled={saving}>{saving ? '…' : t('save')}</button>
        </div>
      </div>
    </div>
  )
}
