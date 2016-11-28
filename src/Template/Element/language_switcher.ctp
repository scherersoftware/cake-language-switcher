<?php
use Cake\I18n\I18n;
?>
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <?= $this->Html->image('LanguageSwitcher.flags/' . $imageMapping[I18n::locale()] . '.png', [
            'style' => 'height: 17px; margin-right: 5px;'
        ]); ?>
        <?= $displayNames[I18n::locale()]; ?>
    </a>
    <ul class="dropdown-menu">
        <?php foreach ($availableLanguages as $language): ?>
            <li>
                <?php
                    $flagImage = $this->Html->image('LanguageSwitcher.flags/' . $imageMapping[$language] . '.png', [
                        'style' => 'height: 17px; margin-right: 5px;'
                    ]);
                    echo $this->Html->link(
                        $flagImage . ' ' . $displayNames[$language],
                        $this->LanguageSwitcher->getUrl($language),
                        [
                            'escape' => false
                        ]
                    );
                ?>
            </li>
        <?php endforeach; ?>
    </ul>
</li>
