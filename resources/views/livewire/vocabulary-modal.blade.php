<div wire:ignore.self id="vocab-modal-component" data-component-id="{{ $componentId }}">

    @if($isOpen)
    <div class="modal-backdrop d-flex justify-content-center align-items-center" style="display: flex !important;">
        <div class="modal-content">
            <h2>{{ $vocabularyId ? __('messages.edit_vocabulary') : __('messages.add_vocabulary') }}</h2>

            <input type="text" wire:model="front" placeholder="{{ __('messages.front') }}" class="form-control mb-2">
            <input type="text" wire:model="back" placeholder="{{ __('messages.back') }}" class="form-control mb-2">
            <textarea wire:model="note" placeholder="{{ __('messages.note') . '(' . __('messages.optional') . ')' }}"
                class="form-control mb-3"></textarea>

            <div class="d-flex justify-content-center gap-3 mt-3">
                <!-- Add/Edit 切り替えボタン -->
                <button wire:click="save" class="btn-red">
                    {{ $vocabularyId ? __('messages.update') : __('messages.add') }}
                </button>

                <!-- Cancel は共通 -->
                <button wire:click="close" class="btn-gray">{{ __('messages.cancel') }}</button>
            </div>
        </div>
    </div>
    @endif
</div>