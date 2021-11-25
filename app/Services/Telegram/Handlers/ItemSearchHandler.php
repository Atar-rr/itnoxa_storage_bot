<?php

namespace App\Services\Telegram\Handlers;

use App\Dto\ItemSearchDto;
use App\Exceptions\DomainException;
use App\Models\ItemProperty;
use App\Models\ItemPropertyBalance;
use App\Models\Storage;
use App\Services\BotUser\BotUserCreateService;
use App\Services\Telegram\ItemSearchService;
use App\Services\Telegram\UserSettingStorageService;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;

class ItemSearchHandler extends BaseCommandHandler
{
    /**
     * @param ItemSearchService $balanceService
     * @param BotUserCreateService $botUserCreateService
     * @param UserSettingStorageService $userSettingStorageService
     */
    public function __construct(
        /** @var ItemSearchService */
        protected ItemSearchService $balanceService,
        /** @var BotUserCreateService */
        protected BotUserCreateService $botUserCreateService,
        /** @var UserSettingStorageService */
        protected UserSettingStorageService $userSettingStorageService,
    ) {
        parent::__construct($botUserCreateService, $userSettingStorageService);
    }

    /**
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handler(Command $systemCommand): ServerResponse
    {
        $message = $systemCommand->getMessage();
        $text = '';


        if ($message->getText() === null) {
            return $systemCommand->replyToUser('Возможно вы удалили свое сообщение. Повторите запрос.');
        }

        $itemSearchDto = new ItemSearchDto(
            $message->getText(),
            $message->getChat()->getId(),
        );

        $this->registrationUserIfNotExist($message->getChat()->getId(), $message->getChat()->getUsername());

        try {
            #TODO полный бред
            [$item, $userStorageIds] = $this->balanceService->findByArticle($itemSearchDto);

            $userStorageIds = array_flip($userStorageIds);
            #TODO кажется все это должно быть в объекте ответа, чтобы не нужно было к БД обращаться -_-
            $itemBalanceInStorages = [];
            /** @var ItemProperty $property */
            foreach ($item->properties()->orderBy(ItemProperty::COL_NAME)->get() as $property) {
                /** @var ItemPropertyBalance $balance */
                foreach ($property->balance()->get() as $balance) {
                    /** @var Storage $storage */
                    $storage = $balance->storage()->first();
                    if (!array_key_exists($storage->id, $userStorageIds)) {
                        continue;
                    }

                    if(!isset($itemBalanceInStorages[$storage->name])) {
                        $itemBalanceInStorages[$storage->name][] =
                            hex2bin('F09F8E81')
                            . ' ' . $storage->name
                            . ":\n______\n";
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
