<template>
    <card class="card" @submitted="updatePassword">
        <template #title>
            Cambio de contraseña
        </template>

        <template #content>
            <div class="mb-5">
                Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantenerse segura.
            </div>
            <div class="field">
                <label for="current_password">Contraseña actual</label>
                <InputText
                    id="current_password"
                    class="w-full"
                    placeholder="Ingresa tu contraseña"
                    :class="{'p-invalid': form.errors.current_password}"
                    type="password"
                    v-model="form.current_password"
                />
                <small v-if="form.errors.current_password" class="p-invalid">
                    {{ form.errors.current_password }}
                </small>
            </div>
            <div class="field">
                <label for="password">Nueva contraseña</label>
                <InputText
                    id="password"
                    class="w-full"
                    placeholder="Ingresa tu contraseña"
                    :class="{'p-invalid': form.errors.password}"
                    type="password"
                    v-model="form.password"
                />
                <small v-if="form.errors.password" class="p-invalid">
                    {{ form.errors.password }}
                </small>
            </div>
            <div class="field">
                <label for="password_confirmation">Vuelve a introducir la nueva contraseña</label>
                <InputText
                    id="password_confirmation"
                    class="w-full"
                    placeholder="Ingresa tu contraseña"
                    :class="{'p-invalid': form.errors.password_confirmation}"
                    type="password"
                    v-model="form.password_confirmation"
                />
                <small v-if="form.errors.password_confirmation" class="p-invalid">
                    {{ form.errors.password_confirmation }}
                </small>
            </div>
        </template>

        <template #footer>
            <Button
                label="Actualizar"
                :loading="form.processing"
                @click="updatePassword"
            />
        </template>
    </card>
</template>

<script>
import Card from "primevue/card";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import FlashMessage from "../../../Services/FlashMessage";

export default {
    components: {
        Card,
        Button,
        InputText,
    },
    mixins: [FlashMessage],
    data() {
        return {
            form: this.$inertia.form({
                current_password: '',
                password: '',
                password_confirmation: '',
            }),
        }
    },

    methods: {
        updatePassword() {
            this.form.put(route('user-password.update'), {
                errorBag: 'updatePassword',
                preserveScroll: true,
                onSuccess: () => {
                    this.form.reset();
                    this.flashSuccess('Contraseña guardada.');
                },
                onError: () => {
                    if (this.form.errors.password) {
                        this.form.reset('password', 'password_confirmation')
                        this.$refs.password.focus()
                    }

                    if (this.form.errors.current_password) {
                        this.form.reset('current_password')
                        this.$refs.current_password.focus()
                    }
                }
            })
        },
    },
}
</script>
