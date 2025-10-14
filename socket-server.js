const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const cors = require('cors');
const bodyParser = require('body-parser');

const app = express();
app.use(cors());
app.use(bodyParser.json());

const server = http.createServer(app);
const io = new Server(server, {
  cors: {
    origin: '*',
    methods: ['GET', 'POST']
  }
});

const SECRET = process.env.SOCKET_SERVER_SECRET || '';

io.on('connection', (socket) => {
  console.log('Socket connected', socket.id);

  socket.on('join', (data) => {
    if (!data || !data.id) return;
    const room = `document_${data.id}`;
    socket.join(room);
    console.log(`Socket ${socket.id} joined room ${room}`);
  });

  socket.on('leave', (data) => {
    if (!data || !data.id) return;
    const room = `document_${data.id}`;
    socket.leave(room);
    console.log(`Socket ${socket.id} left room ${room}`);
  });

  socket.on('disconnect', () => {
    console.log('Socket disconnected', socket.id);
  });
});

// Simple HTTP endpoint for Laravel to POST message broadcasts
app.post('/broadcast', (req, res) => {
  const token = req.get('X-SOCKET-SECRET') || '';
  if (SECRET && token !== SECRET) {
    return res.status(403).json({ success: false, message: 'Invalid secret' });
  }

  const payload = req.body;
  if (!payload || (!payload.document_request_id && !payload.document_request)) {
    return res.status(400).json({ success: false, message: 'Missing document_request_id' });
  }

  const id = payload.document_request_id || (payload.document_request && payload.document_request.id);
  const room = `document_${id}`;

  io.to(room).emit('message', payload.message || payload);

  console.log(`Broadcasted to room ${room}`, payload.message || payload);

  return res.json({ success: true });
});

const PORT = process.env.SOCKET_PORT || 6001;
server.listen(PORT, () => {
  console.log(`Socket server listening on port ${PORT}`);
});
