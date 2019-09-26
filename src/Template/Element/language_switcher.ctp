<?php
use Cake\I18n\I18n;

$style = 'height: 17px;';
if ($renderToggleButtonDisplayName) {
    $style .= 'margin-right: 5px;';
}
$options['style'] = $style;
?>
<li class="dropdown language-switcher">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <?= $this->Html->image('LanguageSwitcher.flags/' . $imageMapping[I18n::getLocale()] . '.png', $options); ?>
        <?php if ($renderToggleButtonDisplayName): ?>
            <?= $displayNames[I18n::getLocale()]; ?>
        <?php endif; ?>
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
