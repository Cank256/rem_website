import { useState, useRef } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';

// ── Icons ─────────────────────────────────────────────────────────────────────
function IconTerminal() {
    return (
        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
    );
}

function IconWarning() {
    return (
        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
        </svg>
    );
}

function IconCheck() {
    return (
        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
        </svg>
    );
}

function IconSpinner() {
    return (
        <svg className="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
            <path className="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
        </svg>
    );
}

// ── Command groups ─────────────────────────────────────────────────────────────
const GROUPS = [
    {
        title: 'Dependencies',
        keys: ['composer-install', 'npm-install', 'npm-build'],
    },
    {
        title: 'Application',
        keys: ['key-generate', 'storage-link', 'filament-upgrade'],
    },
    {
        title: 'Database',
        keys: ['migrate', 'migrate-fresh', 'db-seed'],
    },
    {
        title: 'Cache',
        keys: ['cache-clear', 'config-cache', 'route-cache', 'view-cache'],
    },
];

// ── Status badge ──────────────────────────────────────────────────────────────
function StatusBadge({ status }) {
    if (!status) return null;
    const map = {
        running: { bg: 'bg-blue-100 text-blue-700',   icon: <IconSpinner />, label: 'Running' },
        success: { bg: 'bg-green-100 text-green-700',  icon: <IconCheck />,   label: 'Done' },
        error:   { bg: 'bg-red-100 text-red-700',      icon: <IconWarning />, label: 'Error' },
    };
    const s = map[status];
    return (
        <span className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium ${s.bg}`}>
            {s.icon} {s.label}
        </span>
    );
}

// ── Confirmation modal ────────────────────────────────────────────────────────
function ConfirmModal({ command, onConfirm, onCancel }) {
    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div className="bg-white rounded-xl shadow-2xl p-6 max-w-sm w-full mx-4">
                <div className="flex items-center gap-3 mb-4">
                    <div className="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                        <IconWarning />
                    </div>
                    <h3 className="text-lg font-semibold text-gray-900">Destructive Action</h3>
                </div>
                <p className="text-gray-600 mb-2">
                    <strong>{command.label}</strong> will permanently alter or wipe data.
                </p>
                <p className="text-sm text-gray-500 mb-6">{command.description}</p>
                <div className="flex gap-3 justify-end">
                    <button onClick={onCancel}
                        className="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium">
                        Cancel
                    </button>
                    <button onClick={onConfirm}
                        className="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 text-sm font-medium">
                        Yes, run it
                    </button>
                </div>
            </div>
        </div>
    );
}

// ── Main page ─────────────────────────────────────────────────────────────────
export default function SetupIndex({ commands }) {
    const commandMap = Object.fromEntries(commands.map(c => [c.key, c]));

    const [statuses, setStatuses]   = useState({});   // key → 'running' | 'success' | 'error'
    const [outputs, setOutputs]     = useState({});   // key → string
    const [activeKey, setActiveKey] = useState(null); // which output panel is open
    const [confirm, setConfirm]     = useState(null); // command waiting for confirmation
    const outputRefs                = useRef({});

    const scrollToBottom = (key) => {
        const el = outputRefs.current[key];
        if (el) el.scrollTop = el.scrollHeight;
    };

    const runCommand = async (key, confirmed = false) => {
        const cmd = commandMap[key];
        if (!cmd) return;

        // Destructive — show modal first
        if (cmd.destructive && !confirmed) {
            setConfirm(cmd);
            return;
        }

        setConfirm(null);
        setActiveKey(key);
        setStatuses(s => ({ ...s, [key]: 'running' }));
        setOutputs(o => ({ ...o, [key]: '' }));

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            const response = await fetch(route('setup.run'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'text/plain',
                },
                body: JSON.stringify({ command: key, confirmed }),
            });

            if (!response.ok) {
                const err = await response.json().catch(() => ({ error: response.statusText }));
                setOutputs(o => ({ ...o, [key]: err.error || 'Request failed.' }));
                setStatuses(s => ({ ...s, [key]: 'error' }));
                return;
            }

            // Stream the response body
            const reader = response.body.getReader();
            const decoder = new TextDecoder();

            while (true) {
                const { done, value } = await reader.read();
                if (done) break;
                const chunk = decoder.decode(value, { stream: true });
                setOutputs(o => {
                    const updated = (o[key] || '') + chunk;
                    return { ...o, [key]: updated };
                });
                scrollToBottom(key);
            }

            setStatuses(s => ({ ...s, [key]: 'success' }));
        } catch (err) {
            setOutputs(o => ({ ...o, [key]: String(err) }));
            setStatuses(s => ({ ...s, [key]: 'error' }));
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-2">
                    <IconTerminal />
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Setup &amp; Maintenance
                    </h2>
                </div>
            }
        >
            <Head title="Setup" />

            {confirm && (
                <ConfirmModal
                    command={confirm}
                    onConfirm={() => runCommand(confirm.key, true)}
                    onCancel={() => setConfirm(null)}
                />
            )}

            <div className="py-10">
                <div className="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 space-y-8">

                    {/* Warning banner */}
                    <div className="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-800">
                        <IconWarning />
                        <p>
                            This panel runs server commands directly. Only use it in a trusted environment
                            and restrict access to admin users only.
                        </p>
                    </div>

                    {/* Command groups */}
                    {GROUPS.map(group => {
                        const groupCommands = group.keys
                            .map(k => commandMap[k])
                            .filter(Boolean);

                        return (
                            <div key={group.title} className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <div className="px-6 py-4 border-b border-gray-100 bg-gray-50">
                                    <h3 className="font-semibold text-gray-700 text-sm uppercase tracking-wide">
                                        {group.title}
                                    </h3>
                                </div>

                                <div className="divide-y divide-gray-100">
                                    {groupCommands.map(cmd => (
                                        <div key={cmd.key}>
                                            {/* Command row */}
                                            <div className="flex items-center justify-between px-6 py-4 gap-4">
                                                <div className="flex-1 min-w-0">
                                                    <div className="flex items-center gap-2 flex-wrap">
                                                        <span className="font-medium text-gray-900">{cmd.label}</span>
                                                        {cmd.destructive && (
                                                            <span className="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                                <IconWarning /> Destructive
                                                            </span>
                                                        )}
                                                        <StatusBadge status={statuses[cmd.key]} />
                                                    </div>
                                                    <p className="text-sm text-gray-500 mt-0.5">{cmd.description}</p>
                                                </div>

                                                <div className="flex items-center gap-2 shrink-0">
                                                    {outputs[cmd.key] !== undefined && (
                                                        <button
                                                            onClick={() => setActiveKey(activeKey === cmd.key ? null : cmd.key)}
                                                            className="text-xs text-indigo-600 hover:underline"
                                                        >
                                                            {activeKey === cmd.key ? 'Hide output' : 'Show output'}
                                                        </button>
                                                    )}
                                                    <button
                                                        onClick={() => runCommand(cmd.key)}
                                                        disabled={statuses[cmd.key] === 'running'}
                                                        className={`inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition-colors
                                                            ${cmd.destructive
                                                                ? 'bg-red-600 hover:bg-red-700 text-white disabled:bg-red-300'
                                                                : 'bg-indigo-600 hover:bg-indigo-700 text-white disabled:bg-indigo-300'
                                                            }`}
                                                    >
                                                        {statuses[cmd.key] === 'running' ? (
                                                            <><IconSpinner /> Running…</>
                                                        ) : 'Run'}
                                                    </button>
                                                </div>
                                            </div>

                                            {/* Output panel */}
                                            {activeKey === cmd.key && outputs[cmd.key] !== undefined && (
                                                <div className="px-6 pb-4">
                                                    <pre
                                                        ref={el => outputRefs.current[cmd.key] = el}
                                                        className="bg-gray-950 text-green-400 text-xs rounded-lg p-4 overflow-auto max-h-72 whitespace-pre-wrap font-mono leading-relaxed"
                                                    >
                                                        {outputs[cmd.key] || 'Waiting for output…'}
                                                    </pre>
                                                </div>
                                            )}
                                        </div>
                                    ))}
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
