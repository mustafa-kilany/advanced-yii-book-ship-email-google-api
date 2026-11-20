const http=require('node:http');
const shipments=[{id:'MD-1001',status:'Processing',carrier:'Demo Express',recipient:'Synthetic Customer'}];
function json(res,status,data){res.writeHead(status,{'Content-Type':'application/json'});res.end(JSON.stringify(data))}
const server=http.createServer((req,res)=>{
 const u=new URL(req.url,'http://localhost');
 if(req.method==='GET'&&u.pathname==='/')return json(res,200,{name:'Book Shipping Demo',endpoints:['POST /api/shipments','GET /api/shipments/:id','POST /api/shipments/:id/advance']});
 if(req.method==='GET'&&u.pathname.startsWith('/api/shipments/')){const id=u.pathname.split('/').pop();const s=shipments.find(x=>x.id===id);return s?json(res,200,s):json(res,404,{error:'Shipment not found'})}
 if(req.method==='POST'&&u.pathname.startsWith('/api/shipments/')&&u.pathname.endsWith('/advance')){const id=u.pathname.split('/')[3];const s=shipments.find(x=>x.id===id);if(!s)return json(res,404,{error:'Shipment not found'});const states=['Processing','In Transit','Out for Delivery','Delivered'];s.status=states[Math.min(states.indexOf(s.status)+1,states.length-1)];return json(res,200,s)}
 if(req.method==='POST'&&u.pathname==='api/shipments'){let body='';req.on('data',c=>body+=c);return req.on('end',()=>{const input=JSON.parse(body||'{}');const s={id:`MD-${1000+shipments.length+1}`,status:'Processing',carrier:input.carrier||'Demo Express',recipient:input.recipient||'Synthetic Customer'};shipments.push(s);json(res,201,s)})}
 json(res,404,{error:'Not found'});
});
if(require.main===module)server.listen(process.env.PORT||4001,()=>console.log('Shipping demo running'));
module.exports={server,shipments};
