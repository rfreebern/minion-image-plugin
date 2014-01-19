<?php

namespace Minion\Plugins;

require 'vendor/autoload.php';

$Image = new \Minion\Plugin(
    'Image',
    'Images for minions.',
    'Ryan N. Freebern / ryan@freebern.org'
);

return $Image

->on('PRIVMSG', function ($data) use ($Image) {
    list ($command, $arguments) = $Image->simpleCommand($data);
    if ($command == 'image') {
        $target = $data['arguments'][0];
        if ($target == $Image->Minion->State['Nickname']) {
                list ($target, $ident) = explode('!', $data['source']);
        }
        if (count($arguments)) {
            $results = \rfreebern\GoogleImageSearch::search(implode(' ', $arguments));
            $numResults = count($results);
            if ($numResults) {
                $result = $results[floor(rand(0, $numResults - 1))]['url'];
                $Image->Minion->msg($result, $target);
            } else {
                $Image->Minion->msg('Nothing found.', $target);
            }
        }
    }
});
