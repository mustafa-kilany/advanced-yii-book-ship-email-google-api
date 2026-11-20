const test=require('node:test');const assert=require('node:assert/strict');const {shipments}=require('./server');
test('shipments use synthetic recipients',()=>assert.equal(shipments[0].recipient,'Synthetic Customer'));
test('initial shipment has a status',()=>assert.ok(shipments[0].status));
