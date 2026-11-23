const test = require('node:test');
const assert = require('node:assert/strict');
const { server, shipments } = require('./server');

let baseUrl;

test.before(async () => {
  await new Promise((resolve) => server.listen(0, resolve));
  baseUrl = `http://localhost:${server.address().port}`;
});

test.after(() => server.close());

test('shipments use synthetic recipients', () => {
  assert.equal(shipments[0].recipient, 'Synthetic Customer');
});

test('GET / lists endpoints', async () => {
  const res = await fetch(`${baseUrl}/`);
  assert.equal(res.status, 200);
  assert.ok((await res.json()).endpoints.length > 0);
});

test('POST /api/shipments creates a shipment', async () => {
  const res = await fetch(`${baseUrl}/api/shipments`, {
    method: 'POST',
    body: JSON.stringify({ carrier: 'Test Carrier' }),
  });
  assert.equal(res.status, 201);
  const body = await res.json();
  assert.equal(body.carrier, 'Test Carrier');
  assert.equal(body.status, 'Processing');
});

test('POST /api/shipments rejects invalid JSON', async () => {
  const res = await fetch(`${baseUrl}/api/shipments`, { method: 'POST', body: '{not json' });
  assert.equal(res.status, 400);
});

test('GET /api/shipments/:id returns 404 for unknown id', async () => {
  const res = await fetch(`${baseUrl}/api/shipments/NOPE`);
  assert.equal(res.status, 404);
});

test('POST /api/shipments/:id/advance moves status forward and stops at Delivered', async () => {
  const advance = () => fetch(`${baseUrl}/api/shipments/MD-1001/advance`, { method: 'POST' }).then((r) => r.json());
  assert.equal((await advance()).status, 'In Transit');
  await advance();
  await advance();
  assert.equal((await advance()).status, 'Delivered');
});
