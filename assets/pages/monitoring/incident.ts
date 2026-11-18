import Page from '@wexample/symfony-loader/js/Class/Page';
import RoutingService from '@wexample/symfony-loader/js/Services/RoutingService';
import { unwrapApiEnvelope } from '@wexample/js-api-entity/Common/ApiEnvelope';

const ROUTE_DECIDE = 'api_monitoring_incident_decide';

export default class extends Page {
  pageReady() {
    for (const button of this.el?.querySelectorAll<HTMLButtonElement>('.monitoring--decision') ?? []) {
      button.addEventListener('click', () => {
        void this.decide(button);
      });
    }
  }

  // Sends the decision with its note, then shows the incident as it now stands.
  private async decide(button: HTMLButtonElement): Promise<void> {
    const section = button.closest<HTMLElement>('.monitoring--decide');
    const note = section?.querySelector<HTMLTextAreaElement>('.monitoring--note')?.value ?? '';
    const url = (this.app.getService(RoutingService) as RoutingService).path(ROUTE_DECIDE, {
      id: section?.dataset.incidentId,
      decision: button.dataset.decision,
    });

    button.disabled = true;

    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ note: note.trim() === '' ? null : note }),
      });
      unwrapApiEnvelope(await response.json());
      window.location.reload();
    } finally {
      button.disabled = false;
    }
  }
}
