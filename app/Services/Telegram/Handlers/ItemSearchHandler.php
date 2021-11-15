<?php

namespace App\Services\Telegram\Handlers;

use App\Dto\ItemSearchDto;
use App\Exceptions\DomainException;
use App\Models\ItemProperty;
use App\Models\ItemPropertyBalance;
use App\Models\Storage;
use App\Services\Telegram\ItemSearchService;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;

class ItemSearchHandler extends BaseCommandHandler
{
    public function __construct(
        protected ItemSearchService $balanceService
    ) {}

    /**
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handler(Command $systemCommand): ServerResponse
    {
        $message = $systemCommand->getMessage();
        $text = '';
        $itemSearchDto = new ItemSearchDto(
            $message->getText(),
            $message->getChat()->getId(),
        );

        try {
            $item = $this->balanceService->findByArticle($itemSearchDto);

            #TODO кажется все это должно быть в объекте ответа, чтобы не нужно было к БД обращаться -_-
            $itemBalanceInStorages = [];
            /** @var ItemProperty $property */
            foreach ($item->properties()->orderBy(ItemProperty::COL_NAME)->get() as $property) {
                /** @var ItemPropertyBalance $balance */
                foreach ($property->balance()->get() as $balance) {
                    /** @var Storage $storage */
                    $storage = $balance->storage()->first();
                    if(!isset($itemBalanceInStorages[$storage->name])) {
                        $itemBalanceInStorages[$storage->name][] = $storage->name . ":\n______\n";
                    }
                    $itemBalanceInStorages[$storage->name][] = "$item->article | $property->name | $balance->quantity |\n";
                }
            }
            foreach ($itemBalanceInStorages as $itemBalanceInStorage) {
                $text .= implode('', $itemBalanceInStorage) . "\n";
            }

        } catch (DomainException $e) {
            #TODO рефакторинг, получилось очень сложно

            // Если не удалось найти по артикулу, пойдем поищем по имени
            if ($e->getMessage() === ItemSearchService::ERROR_ITEM_NOT_FOUND) {
                try {
                    $items = $this->balanceService->findByName($itemSearchDto);
                    foreach ($items as $item) {
                        $text .= "$item->article | $item->name \n";
                    }
                } catch (DomainException $e) {
                    $text = $e->getMessage();
                }
            } else {
                $text = $e->getMessage();
            }
        }

       return $systemCommand->replyToUser($text);
    }

}
