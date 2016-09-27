<?php
/**
 * Created by PhpStorm.
 * User: Анатоли
 * Date: 29.08.2016
 * Time: 21:14
 */
//namespace Stem;
define('MODE', 'u');
define('RVRE', '/^(.*?[аеёиоуыэюя])(.*)$/'.MODE);
define('PERFECTIVEGROUND', '/((ив|ивши|ившись|ыв|ывши|ывшись)|((?<=[ая])(в|вши|вшись)))$/'.MODE);
define('REFLEXIVE', '/(с[яь])$/'.MODE);
define('ADJECTIVE', '/(ее|ие|ые|ое|ими|ыми|ей|ий|ый|ой|ем|им|ым|ом|его|ого|ему|ому|их|ых|ую|юю|ая|яя|ою|ею)$/'.MODE);
define('PARTICIPLE', '/((ивш|ывш|ующ)|((?<=[ая])(ем|нн|вш|ющ|щ)))$/'.MODE);
define('VERB', '/((ила|ыла|ена|ейте|уйте|ите|или|ыли|ей|уй|ил|ыл|им|ым|ен|ило|ыло|ено|ят|ует|уют|ит|ыт|ены|ить|ыть|ишь|ую|ю)|((?<=[ая])(ла|на|ете|йте|ли|й|л|ем|н|ло|но|ет|ют|ны|ть|ешь|нно)))$/'.MODE);
define('ADVERB_SUFFIX', '/(жды|-либо|-нибудь|учи|ючи)$/'.MODE);
define('NOUN', '/(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|иям|ям|ием|ем|ам|ом|о|у|ах|иях|ях|ы|ь|ию|ью|ю|ия|ья|я)$/'.MODE);
define('DERIVATIONAL', '/[^аеёиоуыэюя][аеёиоуыэюя]+[^аеёиоуыэюя]+[аеёиоуыэюя].*(?<=о)сть?$/'.MODE);
define('NOUN_SUFFIX', '/(иров|ан|ян|анин|янин|ач|ени|ет|еств|есть|ец|ца|изм|изн|ик|ник|ин|ист|тель|их|иц|ниц|льник|льщик|льщиц|ни|ун|чик|чиц|щик|еньк|оньк|енк|онк|ашк|ищ|ок|инк|очк|ушк|юшк|ышк|ишк|очек|ечк|ушек|ышек|ыш)$/'.MODE);
define('ADJECTIVE_SUFFIX', '/(ал|ел|ан|ян|аст|ев|ов|еват|оват|ен|енн|енск|инск|лив|чив|ив|ин|овит|ит|шн|тельн|уч|чат|еньк|оньк|ехоньк|оханьк|ешеньк|ошеньк)$/'.MODE);
define('VERB_SUFFIX', '/(ка|ева|ова|ыва|ива|нича|ну|ствова|ть|ти|ирова)$/'.MODE);

class Format {
    public static function eh($msg) {
        echo ($msg."<br>");
    }
}

// define('VOWEL', '/аеиоуыэюя/'.MODE); // не используется
class LinguaStemRu
{
public static $LowerMode = FALSE;
public static $RV = '';

private static function clear($pattern)
{
    $uncleared = self::$RV;
    self::$RV = preg_replace($pattern, '', self::$RV);
    $replaced = str_replace(self::$RV, '', $uncleared); if ( $replaced != '' ) Format::eh('Удалено \''.$replaced.'\'');
return $uncleared !== self::$RV;
}


public static function word(&$word)
{
    if ( $LowerMode ) $word = mb_strtolower($word);

    //Для каждого слова:
    // RV — это часть слова после первого глассного
    // R1 — это часть слова после первого согласного, следующего после глассного
    // R2 — это часть слова после первого согласного, следующего после глассного в R1
    if (!preg_match(RVRE, $word, $word_parts) || !$word_parts[2]) return;
    list( ,$start, self::$RV) = $word_parts;

    // [ШАГ 1]
    // Найти окончание ПРИЧАСТИЯ СОВЕРШЕННОГО ВИДА.
    // Если окончание найдено, то удалить его и завершить первый шаг.
    // Иначе удалить окончания ВОЗРАТНОЙ ЧАСТИЦЫ, а затем удалить окончания
    // КАЧЕСТВЕННЫХ, ГЛАГОЛОВ и СУЩЕСТВИТЕЛЬНЫХ, как только одно из окончаний
    // будет удалено, завершить первый шаг.
    Format::eh('Причастия совершенного вида');
    if (!self::clear(PERFECTIVEGROUND)) // Причастие совершенного вида
    {
        Format::eh('Возвратные части');
        self::clear(REFLEXIVE); // Возвратное (сь, ся)
        Format::eh('Окончания прилагательных');
        if (self::clear(ADJECTIVE)) // Прилагательное
        {
            Format::eh('Суффиксы прилагательного');
            self::clear(ADJECTIVE_SUFFIX);
            Format::eh('Окончания причастий');
            self::clear(PARTICIPLE); // Причастие
        }
        else
        {
            Format::eh('Суффиксы наречий');
            if ( !self::clear(ADVERB_SUFFIX) ) // Наречие
            {
                Format::eh('Окончания глаголов');
                if (self::clear(VERB)) // Глагол
                {
                    Format::eh('Суффиксы глаголов');
                    self::clear(VERB_SUFFIX);
                }
                else
                {
                    Format::eh('Окончания существительных');
                    self::clear(NOUN); // Существительное
                    Format::eh('Суффиксы существительных');
                    while (self::clear(NOUN_SUFFIX));
                }
            }
        }
    }

    // [ШАГ 2]
    // Если слово оканчивается на символ 'и' , то удалить его.
    self::clear('/и$/'.MODE);

    // [ШАГ 3]
    // Найти СЛОВООБРАЗУЮЩЕЕ окончание в R2, если окончание найдено, то удалить его.
    if ( preg_match(DERIVATIONAL, self::$RV) ) // Словообразующее
        self::clear('/ость?$/'.MODE);

    // [ШАГ 4]
    // Заменить двойную нн (nn) одинарной, или найти окончание
    // ПРИЛАГАТЕЛЬНОГО ПРЕВОСХОДНОЙ СТЕПЕНИ, если окончание найдено,
    // то удалить его и заменить двойную 'нн' одинарной, или если
    // слово оканчивается на 'ь', то удалить его.
    if (!self::clear('/ь$/'.MODE))
    {
        self::clear('/ейше?/'.MODE);
        self::$RV = preg_replace('/нн$/'.MODE, 'н', self::$RV);
    }

    $word = $start.self::$RV;
    self::$RV = '';
}
};

/*
namespace Stem;
class LinguaStemRu
{
    var $VERSION = "0.02";
    var $Stem_Caching = 0;
    var $Stem_Cache = array();
    var $VOWEL = '/аеиоуыэюя/';
    var $PERFECTIVEGROUND = '/((ив|ивши|ившись|ыв|ывши|ывшись)|((?<=[ая])(в|вши|вшись)))$/';
    var $REFLEXIVE = '/(с[яь])$/';
    var $ADJECTIVE = '/(ее|ие|ые|ое|ими|ыми|ей|ий|ый|ой|ем|им|ым|ом|его|ого|еых|ую|юю|ая|яя|ою|ею)$/';
    var $PARTICIPLE = '/((ивш|ывш|ующ)|((?<=[ая])(ем|нн|вш|ющ|щ)))$/';
    var $VERB = '/((ила|ыла|ена|ейте|уйте|ите|или|ыли|ей|уй|ил|ыл|им|ым|ены|ить|ыть|ишь|ую|ю)|((?<=[ая])(ла|на|ете|йте|ли|й|л|ем|н|ло|но|ет|ют|ны|ть|ешь|нно)))$/';
    var $NOUN = '/(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|и|ы|ь|ию|ью|ю|ия|ья|я)$/';
    var $RVRE = '/^(.*?[аеиоуыэюя])(.*)$/';
    var $DERIVATIONAL = '/[^аеиоуыэюя][аеиоуыэюя]+[^аеиоуыэюя]+[аеиоуыэюя].*(?<=о)сть?$/';
    function __construct() {
        mb_internal_encoding('UTF-8');
    }
    function s(&$s, $re, $to)
    {
        $orig = $s;
        $s = preg_replace($re, $to, $s);
        return $orig !== $s;
    }
    function m($s, $re)
    {
        return preg_match($re, $s);
    }
    function stem_word($word)
    {
        $word = mb_strtolower($word);
        $word = str_replace('ё', 'е', $word); // замена ё на е, что бы учитывалась как одна и та же буква
        # Check against cache of stemmed words
        if ($this->Stem_Caching && isset($this->Stem_Cache[$word])) {
            return $this->Stem_Cache[$word];
        }
        $stem = $word;
        do {
            if (!preg_match($this->RVRE, $word, $p)) break;
            $start = $p[1];
            $RV = $p[2];
            if (!$RV) break;
            # Step 1
            if (!$this->s($RV, $this->PERFECTIVEGROUND, '')) {
                $this->s($RV, $this->REFLEXIVE, '');
                if ($this->s($RV, $this->ADJECTIVE, '')) {
                    $this->s($RV, $this->PARTICIPLE, '');
                } else {
                    if (!$this->s($RV, $this->VERB, ''))
                        $this->s($RV, $this->NOUN, '');
                }
            }
            # Step 2
            $this->s($RV, '/и$/', '');
            # Step 3
            if ($this->m($RV, $this->DERIVATIONAL))
                $this->s($RV, '/ость?$/', '');
            # Step 4
            if (!$this->s($RV, '/ь$/', '')) {
                $this->s($RV, '/ейше?/', '');
                $this->s($RV, '/нн$/', 'н');
            }
            $stem = $start.$RV;

            var_dump($RV);

        } while(false);
        if ($this->Stem_Caching) $this->Stem_Cache[$word] = $stem;
        return $stem;
    }
    function stem_caching($parm_ref)
    {
        $caching_level = @$parm_ref['-level'];
        if ($caching_level) {
            if (!$this->m($caching_level, '/^[012]$/')) {
                die(__CLASS__ . "::stem_caching() - Legal values are '0','1' or '2'. '$caching_level' is not a legal value");
            }
            $this->Stem_Caching = $caching_level;
        }
        return $this->Stem_Caching;
    }
    function clear_stem_cache()
    {
        $this->Stem_Cache = array();
    }
}
*/