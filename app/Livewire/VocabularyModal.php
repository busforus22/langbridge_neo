<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vocabulary;
use Illuminate\Support\Facades\Auth;

class VocabularyModal extends Component
{
    public $front = '';
    public $back = '';
    public $note = '';
    public $vocabularyId = null; // 編集時は既存の単語IDを入れる
    public $isOpen = false;      // モーダル表示フラグ

    protected $listeners = [
        'openVocabularyModal' => 'open',
    ];

    // モーダルを開く
    public function open($front = '', $back = '', $vocabularyId = null, $note = '')
    {
        $this->front = $front;
        $this->back = $back;
        $this->note = $note;
        $this->vocabularyId = $vocabularyId;
        $this->isOpen = true;
    }

    public function mount()
    {
        $this->isOpen = false;
        $this->front = '';
        $this->back = '';
        $this->note = '';
        $this->vocabularyId = null;
    }

    // 保存処理
    public function save()
    {
        $this->validate([
            'front' => 'required|string',
            'back' => 'required|string',
            'note' => 'nullable|string',
        ]);

        Vocabulary::updateOrCreate(
            ['id' => $this->vocabularyId],
            [
                'user_id' => Auth::id(),
                'front' => $this->front,
                'back' => $this->back,
                'note' => $this->note,
                'status' => 'unlearned',
            ]
        );

        $this->close();
        $this->dispatch('vocabularyAdded'); // ページリロード用
        $this->redirect('/vocabulary/index');
    }

    // モーダルを閉じる
    public function close()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.vocabulary-modal', [
            'componentId' => $this->getId()
        ]);
    }
}
