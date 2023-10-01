<x-shared.form.multi-step-form.form>
    <x-slot:header>
        <x-shared.rounded-icon icon="fa-solid fa-key" class="mx-auto"/>

        <x-auth.form.title>Rejestracja</x-auth.form.title>
    </x-slot:header>
    <x-slot:stepper>
        <x-shared.form.multi-step-form.step
            text="Dane użytkownika"
            icon="fa-regular fa-user"
            step="1"
            :currentStep="$currentStep"
            :invalid="$this->isInvalidStep(1)"
            />
        <x-shared.form.multi-step-form.step
            text="Dane logowania"
            icon="fa-solid fa-lock"
            step="2"
            :currentStep="$currentStep"
            :invalid="$this->isInvalidStep(2)"
            />
        <x-shared.form.multi-step-form.step
            text="Wymiary ciała"
            icon="fa-solid fa-ruler"
            step="3"
            :currentStep="$currentStep"
            :invalid="$this->isInvalidStep(3)"
            />
    </x-slot:stepper>
    <x-slot:body>
        {{-- Step 1 - start --}}
        @if ($this->isVisibleSection(1))
        <section>
            <div>
                <x-shared.form.filepond wire:model="avatar" id="avatar" name="avatar" :initialFiles="$this->avatarPath"/>
                <x-shared.form.error field="avatar"/>
            </div>
            <div class="mt-6">
                <x-shared.form.input
                    wire:model.defer="name"
                    id="name"
                    name="name"
                    label="Nazwa"
                    :invalid="$errors->has('name')"
                    required/>
                <x-shared.form.error field="name"/>
            </div>
        </section>
        @endif
        {{-- Step 2 - end --}}
        {{-- Step 2 - start --}}
        @if ($this->isVisibleSection(2))
        <section>
            <div class="mt-6">
                <x-shared.form.input
                    wire:model.lazy="email"
                    id="email"
                    name="email"
                    type="email"
                    label="Adres e-mail"
                    :invalid="$errors->has('email')"
                    required/>
                <x-shared.form.error field="email"/>
            </div>
            <x-shared.form.double-input-container class="mt-6">
                <x-slot:selectInput>
                    <x-shared.form.pre-build-fields.phone-country-field
                        wire:model="phone_country"
                        id="phoneCountry"
                        name="phone_country"
                        label="Kraj"
                        :invalid="$errors->has('phone_country')"
                        required/>
                </x-slot:selectInput>
                <x-slot:mainInput>
                    <x-shared.form.input
                        wire:model.lazy="phone"
                        id="phone"
                        name="phone"
                        type="phone"
                        label="Numer telefonu"
                        :invalid="$errors->has('phone')"
                        required/>
                </x-slot:mainInput>
                <x-slot:errors>
                    <x-shared.form.error field="phone"/>
                    <x-shared.form.error field="phone_country"/>
                </x-slot:errors>
            </x-shared.form.double-input-container>
            <div class="mt-6">
                <x-shared.form.input
                    wire:model.defer="password"
                    id="password"
                    name="password"
                    type="password"
                    label="Hasło"
                    :invalid="$errors->has('password')"
                    required/>

                    <x-shared.form.error field="password"/>
            </div>
            <div class="mt-6">
                <x-shared.form.input
                    wire:model.defer="password_confirmation"
                    id="passwordConfirmation"
                    name="password_confirmation"
                    type="password"
                    label="Potwierdź hasło"
                    :invalid="$errors->has('password_confirmation')"
                    required/>
            </div>
        </section>
        @endif
        {{-- Step 2 - end --}}
        {{-- Step 3 - start --}}
        @if ($this->isVisibleSection(3))
        <section>
            <x-shared.form.double-input-container>
                <x-slot:selectInput>
                    <x-shared.form.pre-build-fields.weight-unit-field
                        wire:model="weight_unit"
                        id="weightUnit"
                        name="weight_unit"
                        label="Jednostka"
                        :invalid="$errors->has('weight_unit')"
                        required/>
                </x-slot:selectInput>
                <x-slot:mainInput>
                    <x-shared.form.input
                        id="weight"
                        name="weight"
                        type="weight"
                        label="Waga"
                        :invalid="$errors->has('weight')"
                        required/>
                </x-slot:mainInput>
                <x-slot:errors>
                    <x-shared.form.error field="weight"/>
                    <x-shared.form.error field="weight_unit"/>
                </x-slot:errors>
            </x-shared.form.double-input-container>
            <x-shared.form.double-input-container class="mt-6">
                <x-slot:selectInput>
                    <x-shared.form.pre-build-fields.height-unit-field
                        wire:model="height_unit"
                        id="heightUnit"
                        name="height_unit"
                        label="Jednostka"
                        :invalid="$errors->has('height_unit')"
                        required/>
                </x-slot:selectInput>
                <x-slot:mainInput>
                    <x-shared.form.input
                        id="height"
                        name="height"
                        type="height"
                        label="Wzrost"
                        :invalid="$errors->has('height')"
                        required/>
                </x-slot:mainInput>
                <x-slot:errors>
                    <x-shared.form.error field="height"/>
                    <x-shared.form.error field="height_unit"/>
                </x-slot:errors>
            </x-shared.form.double-input-container>
        </section>
        @endif
        {{-- Step 3 - end --}}
    </x-slot:body>
    <x-slot:buttons>
        <div class="flex flex-col justify-between mt-4">
            <div class="flex">
                @if (!$this->isFirstStep)
                    <x-shared.form.multi-step-form.button wire:click="prevStep">
                        Poprzedni krok
                    </x-shared.form.multi-step-form.button>
                @endif
                @if (!$this->isLastStep)
                    <x-shared.form.multi-step-form.button wire:click="nextStep" class="ml-auto">
                        Następny krok
                    </x-shared.form.multi-step-form.button>
                @endif
            </div>
            @if ($this->isLastStep)
                    <x-shared.form.multi-step-form.button
                        type="submit"
                        class="mt-4"
                        >
                        Zarejestruj
                    </x-shared.form.multi-step-form.button>
            @endif
            <x-auth.form.back-to-login-page-button class="mt-4"/>
        </div>
    </x-slot:buttons>
</x-shared.form.multi-step-form.form>
