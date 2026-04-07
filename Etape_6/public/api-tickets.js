document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-api-ticket-form]');

    if (!form) {
        return;
    }

    const storeUrl = form.dataset.storeUrl;
    const csrfToken = form.dataset.csrfToken;
    const messageEl = document.getElementById('ticket-message');
    const tbody = document.querySelector('[data-api-ticket-list]');

    const escapeHtml = (value) => String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

    const setMessage = (text, type = 'info') => {
        if (!messageEl) {
            return;
        }

        messageEl.textContent = text;
        messageEl.dataset.type = type;
        messageEl.style.color = type === 'error' ? '#dc2626' : (type === 'success' ? '#16a34a' : '#2563eb');
    };

    const renderRows = (tickets) => {
        // Only render if we're on a list page with tbody
        if (!tbody) return;

        if (!tickets.length) {
            tbody.innerHTML = '<tr><td colspan="8">Aucun ticket disponible.</td></tr>';
            return;
        }

        tbody.innerHTML = tickets.map((ticket) => {
            const hours = Number(ticket.hours_spent ?? 0).toFixed(2).replace('.', ',');
            const projectName = ticket.project_name ?? 'N/A';
            const showUrl = `/tickets/${ticket.id}`;
            const editUrl = `/tickets/${ticket.id}/edit`;
            const destroyUrl = `/tickets/${ticket.id}`;

            return `
                <tr>
                    <td>#${escapeHtml(ticket.id)}</td>
                    <td>${escapeHtml(ticket.title)}</td>
                    <td>${escapeHtml(projectName)}</td>
                    <td>${escapeHtml(ticket.billing_type)}</td>
                    <td>${escapeHtml(ticket.priority)}</td>
                    <td>${escapeHtml(ticket.status)}</td>
                    <td>${escapeHtml(hours)} h</td>
                    <td>
                        <a href="${escapeHtml(showUrl)}">Detail</a>
                        |
                        <a href="${escapeHtml(editUrl)}">Modifier</a>
                        |
                        <form method="POST" action="${escapeHtml(destroyUrl)}" style="display:inline;" onsubmit="return confirm('Supprimer ce ticket ?');">
                            <input type="hidden" name="_token" value="${escapeHtml(csrfToken)}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" style="background:none;border:none;color:#dc2626;cursor:pointer;padding:0;">Supprimer</button>
                        </form>
                    </td>
                </tr>
            `;
        }).join('');
    };

    const loadTickets = async () => {
        // Only load on list pages, not on create pages
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!storeUrl) {
            setMessage('URL API manquante.', 'error');
            return;
        }

        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());

        try {
            setMessage('Creation du ticket en cours...', 'info');
            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken,
                },
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (!response.ok) {
                setMessage(result?.message || 'La creation du ticket a echoue.', 'error');
                return;
            }

            setMessage(result.message ?? 'Ticket ajoute avec succes. Redirection...', 'success');
            setTimeout(() => {
                window.location.href = '/tickets';
            }, 1500);
        } catch (error) {
            setMessage(error.message || 'La creation du ticket a echoue.', 'error');
            console.error(error);
        }
    });
});

