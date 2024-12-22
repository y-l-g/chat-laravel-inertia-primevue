<script setup>
import { onMounted, ref } from 'vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/fr'
import { router, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import 'emoji-picker-element';
import ThemeSwitcher from '../components/ThemeSwitcher.vue';
dayjs.locale('fr') // use loaded locale globally
dayjs.extend(relativeTime);

const props = defineProps({
    messages: Array,
    user_id: Number,
    user_name: String
})

const smileys = ["\u{1F201}", "\u{1F238}", "\u{1F250}", "\u{1F300}", "\u{1F30D}", "\u{1F30F}", "\u{1F316}", "\u{1F319}", "\u{1F31F}", "\u{1F32D}", "\u{1F330}", "\u{1F332}", "\u{1F334}", "\u{1F337}", "\u{1F34B}", "\u{1F350}", "\u{1F37C}", "\u{1F380}", "\u{1F3A0}", "\u{1F3C5}", "\u{1F3E0}", "\u{1F3E4}", "\u{1F3F4}", "\u{1F408}", "\u{1F40F}", "\u{1F411}", "\u{1F413}", "\u{1F42A}", "\u{1F440}", "\u{1F465}", "\u{1F46C}", "\u{1F46E}", "\u{1F4AD}", "\u{1F4B6}", "\u{1F4B8}", "\u{1F4EC}", "\u{1F4EE}", "\u{1F4F5}", "\u{1F4F8}", "\u{1F4FF}", "\u{1F503}", "\u{1F508}", "\u{1F515}", "\u{1F52C}", "\u{1F52E}", "\u{1F54B}", "\u{1F550}", "\u{1F55C}", "\u{1F57A}", "\u{1F5A4}", "\u{1F600}", "\u{1F607}", "\u{1F609}", "\u{1F60E}", "\u{1F615}", "\u{1F61F}", "\u{1F628}", "\u{1F62C}", "\u{1F630}", "\u{1F634}", "\u{1F641}", "\u{1F645}", "\u{1F680}", "\u{1F686}", "\u{1F68C}", "\u{1F694}", "\u{1F69B}", "\u{1F6A2}", "\u{1F6A6}", "\u{1F6AE}", "\u{1F6B2}", "\u{1F6B6}", "\u{1F6B9}", "\u{1F6BF}", "\u{1F6CC}", "\u{1F6D5}", "\u{1F6DC}", "\u{1F6EB}", "\u{1F6F4}", "\u{1F6F7}", "\u{1F6F9}", "\u{1F7E0}", "\u{1F7F0}", "\u{1F910}", "\u{1F919}", "\u{1F91F}", "\u{1F928}", "\u{1F930}", "\u{1F933}", "\u{1F93C}", "\u{1F93F}", "\u{1F947}", "\u{1F94C}", "\u{1F950}", "\u{1F95F}", "\u{1F96C}", "\u{1F971}", "\u{1F977}", "\u{1F979}", "\u{1F980}", "\u{1F985}", "\u{1F992}", "\u{1F998}", "\u{1F9A3}", "\u{1F9A5}", "\u{1F9AB}", "\u{1F9AE}", "\u{1F9B0}", "\u{1F9BA}", "\u{1F9C0}", "\u{1F9C3}", "\u{1F9CB}", "\u{1F9D0}", "\u{1F9E7}", "\u{1FA70}", "\u{1FA74}", "\u{1FA78}", "\u{1FA7B}", "\u{1FA80}", "\u{1FA83}", "\u{1FA87}"]


const op = ref();
const toggle = (event) => {
    op.value.toggle(event);
}

const sP = ref(null)
const scrollToBottom = () => {
    sP.value.$refs.content.scrollTop = sP.value.$refs.content.scrollHeight
}
onMounted(() => {
    scrollToBottom();
})

const form = useForm({
    content: ""
})

window.Echo.channel('messages')
    .listen("MessageReceived", (response => {
        router.reload()
    }))

</script>

<template>

    <div class="
        sm:p-[8%]
        p-5
        max-w-6xl
        h-screen
        m-auto
        flex-col
        flex">
        <ThemeSwitcher class="sticky top-0" />
        <ScrollPanel
            ref="sP"
            class="
            rounded-t-xl
            p-4
            h-[80%]
            dark:bg-surface-900
            bg-surface-100
            border-b
            border-b-primary-500"
        >
            <div class="flex flex-col-reverse">
                <div
                    v-for="message in messages"
                    :class="user_id === message.user.id ? 'my-3 flex flex-col items-end' : 'my-3 flex flex-col items-start'"
                >
                    <p class="font-medium pb-1">{{ user_id === message.user.id ? "Moi" : message.user.name }}
                    </p>
                    <p class="max-w-[400px] break-all inline-block py-1 bg-primary-100 dark:bg-black px-2 rounded-xl">
                        {{ message.content }}</p>
                    <p class="py-1 text-xs">{{ dayjs(message.created_at).fromNow() }}<i
                            class="pi pi-check ml-2 text-primary-400"
                            style="font-size: 0.5rem"
                        ></i>
                    </p>
                </div>
            </div>
        </ScrollPanel>
        <form
            class="flex p-5 dark:bg-surface-900 bg-surface-100 rounded-b-xl"
            @submit.prevent="form.post('/', {
                preserveScroll: true,
                preserveState: true
            });
            form.content = '';
            scrollToBottom()"
        >
            <IftaLabel class="grow">
                <Textarea
                    @keydown.enter.exact.prevent
                    @keyup.enter="form.post('/', {
                        preserveScroll: true,
                        preserveState: true
                    });
                    form.content = '';
                    scrollToBottom()"
                    id="message"
                    class="w-full"
                    v-model="form.content"
                />
                <label for="message">Message</label>
            </IftaLabel>
            <div class="flex flex-wrap">
                <Button
                    icon="
                pi
                pi-face-smile"
                    text
                    rounded
                    size="large"
                    @click="toggle"
                >
                </Button>
                <Button
                    icon="
                pi
                pi-send"
                    text
                    type="submit"
                    rounded
                    size="large"
                ></Button>
            </div>

        </form>
        <Message
            v-if="form.errors.content"
            severity="error"
        >{{ form.errors.content }}</Message>
    </div>
    <Popover ref="op">
        <ScrollPanel class="h-[200px]">
            <div class="flex flex-wrap gap-4 w-[25rem]">
                <Button
                    v-for="smiley in smileys"
                    plain
                    text
                    @click="form.content += smiley"
                >{{ smiley }}</Button>
            </div>
        </ScrollPanel>
    </Popover>

</template>
