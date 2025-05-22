@extends('adminlte::page')

@section('title', ' Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Bienvenido a tu panel</h3>
        </div>
        <div class="card-body">
            <p>Aquí puedes gestionar tu aplicación.</p>
        </div>
    </div>

    <div style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; display: flex; flex-direction: column; align-items: flex-end;">
        <div style="margin-bottom: 10px;">
            <div x-data="chatbot()" style="z-index: 1001;">
                <button @click="toggleChat" class="btn btn-lg shadow-lg" style="background: linear-gradient(135deg, #FF9800, #FF5722); color: white; border-radius: 50px; padding: 15px; border: none;">
                    <i class="fas fa-comment-dots"></i>
                </button>
                <div x-show="isOpen" x-transition class="card shadow-lg" style="width: 350px; height: 450px; border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); display: flex; flex-direction: column;">
                    <div class="card-header" style="background: linear-gradient(135deg, #FF9800, #FF5722); color: white; border-radius: 15px 15px 0 0; display: flex; justify-content: space-between; align-items: center; padding: 10px;">
                        <h3 class="card-title mb-0" style="margin-right: auto;">Asistente Virtual</h3>
                        <button @click="isOpen = false" class="btn btn-tool text-white" style="margin-left: auto;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="card-body p-3 overflow-auto" style="flex-grow: 1; background-color: #f0f0f0;">
                        <template x-for="(message, index) in messages" :key="index">
                            <div class="mb-2" :class="{ 'text-left': message.sender === 'bot', 'text-right': message.sender === 'user' }">
                                <div class="chat-bubble" :class="{ 'user': message.sender === 'user', 'bot': message.sender === 'bot' }">
                                    <div x-html="message.text"></div>
                                </div>
                                <div class="text-xs text-muted mt-1" x-text="message.sender === 'user' ? 'Tú' : 'Asistente'"></div>
                            </div>
                        </template>
                    </div>
                    <div class="card-footer">
                        <form @submit.prevent="sendMessage" class="form-inline">
                            <div class="input-group w-100">
                                <input x-model="inputMessage" type="text" class="form-control" placeholder="Escribe tu mensaje..." required @keyup.enter="sendMessage">
                                <div class="input-group-append">
                                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #FF9800, #FF5722); color: white;">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <a href="https://wa.me/5219993010426" target="_blank" class="btn btn-lg shadow-lg" style="background-color: #25D366; color: white; border-radius: 50px; padding: 15px; border: none;">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </div>

    <style>
        .chat-bubble {
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 10px;
            display: inline-block;
            max-width: 80%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .chat-bubble.user {
            background: linear-gradient(135deg, #FF9800, #FF5722);
            color: white;
            border-radius: 20px 20px 0 20px;
        }

        .chat-bubble.bot {
            background-color: #e8e8e8;
            color: black;
            border-radius: 20px 20px 20px 0;
        }
    </style>
@stop

@section('js')
<script>
    function chatbot() {
        return {
            isOpen: false,
            inputMessage: '',
            messages: [],

            init() {
                this.addMessage('Hola, soy tu asistente virtual. ¿En qué puedo ayudarte?', 'bot');
            },

            toggleChat() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.scrollToBottom();
                }
            },

            addMessage(text, sender) {
                this.messages.push({
                    text: text,
                    sender: sender
                });
                this.scrollToBottom();
            },

            sendMessage() {
                if (!this.inputMessage.trim()) return;

                const message = this.inputMessage;
                this.addMessage(message, 'user');
                this.inputMessage = '';

                fetch('{{ route("chatbot.handle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(response => response.json())
                .then(data => {
                    this.addMessage(data.response, 'bot');
                });
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const container = this.$el.querySelector('.card-body');
                    container.scrollTop = container.scrollHeight;
                });
            }
        }
    }
</script>
@stop