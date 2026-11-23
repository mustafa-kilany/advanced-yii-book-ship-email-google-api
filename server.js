const http = require('node:http');

const STATUSES = ['Processing', 'In Transit', 'Out for Delivery', 'Delivered'];
const MAX_BODY_BYTES = 1e6;

const shipments = [
  { id: 'MD-1001', status: 'Processing', carrier: 'Demo Express', recipient: 'Synthetic Customer' },
];

function sendJson(res, status, data) {
  res.writeHead(status, { 'Content-Type': 'application/json' });
  res.end(JSON.stringify(data));
}

function readJsonBody(req) {
  return new Promise((resolve, reject) => {
    let body = '';
    req.on('data', (chunk) => {
      body += chunk;
      if (body.length > MAX_BODY_BYTES) {
        reject(new Error('Body too large'));
        req.destroy();
      }
    });
    req.on('end', () => {
      try {
        resolve(JSON.parse(body || '{}'));
      } catch {
        reject(new Error('Invalid JSON'));
      }
    });
    req.on('error', reject);
  });
}

function findShipment(id) {
  return shipments.find((s) => s.id === id);
}

async function handle(req, res) {
  const { pathname } = new URL(req.url, 'http://localhost');
  const parts = pathname.split('/').filter(Boolean); // e.g. ['api', 'shipments', 'MD-1001', 'advance']

  if (req.method === 'GET' && pathname === '/') {
    return sendJson(res, 200, {
      name: 'Book Shipping Demo',
      endpoints: ['POST /api/shipments', 'GET /api/shipments/:id', 'POST /api/shipments/:id/advance'],
    });
  }

  if (parts[0] !== 'api' || parts[1] !== 'shipments') {
    return sendJson(res, 404, { error: 'Not found' });
  }

  // POST /api/shipments
  if (req.method === 'POST' && parts.length === 2) {
    let input;
    try {
      input = await readJsonBody(req);
    } catch (err) {
      return sendJson(res, 400, { error: err.message });
    }
    const shipment = {
      id: `MD-${1000 + shipments.length + 1}`,
      status: STATUSES[0],
      carrier: input.carrier || 'Demo Express',
      recipient: input.recipient || 'Synthetic Customer',
    };
    shipments.push(shipment);
    return sendJson(res, 201, shipment);
  }

  // GET /api/shipments/:id
  if (req.method === 'GET' && parts.length === 3) {
    const shipment = findShipment(parts[2]);
    return shipment ? sendJson(res, 200, shipment) : sendJson(res, 404, { error: 'Shipment not found' });
  }

  // POST /api/shipments/:id/advance
  if (req.method === 'POST' && parts.length === 4 && parts[3] === 'advance') {
    const shipment = findShipment(parts[2]);
    if (!shipment) return sendJson(res, 404, { error: 'Shipment not found' });
    const next = Math.min(STATUSES.indexOf(shipment.status) + 1, STATUSES.length - 1);
    shipment.status = STATUSES[next];
    return sendJson(res, 200, shipment);
  }

  return sendJson(res, 404, { error: 'Not found' });
}

const server = http.createServer((req, res) => {
  handle(req, res).catch(() => sendJson(res, 500, { error: 'Internal server error' }));
});

if (require.main === module) {
  const port = process.env.PORT || 4001;
  server.listen(port, () => console.log(`Shipping demo running on http://localhost:${port}`));
}

module.exports = { server, shipments, STATUSES };
