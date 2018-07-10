# ScoutOrg
ScoutOrg är ett bibliotek i php som innehåller en datastruktur för en scoutorganisation. Biblioteket är skrivet för att vara grunden till många olika applikationer där organisationen hanteras på scoutkårers hemsidor. Datastrukturen är skriven så att den fungerar bäst för data som hämtas från scoutnet. Biblioteket är dock skrivet så att det även går att implementera andra datakällor som en egen databas eller en blandning av en egen databas och scoutnet.
Det finns ett förhoppningsvis uppdaterat [klassdiagram](https://github.com/scouternasetjanster/ScoutOrg/blob/master/docs/classDiag.asta) som kan läsas med [astah community](http://astah.net/editions/community).
Just nu finns endast support för joomla men utrymme finns att skapa en plugin för t.ex. wordpress.

## Installation och användning
Just nu finns endast installation för joomla och detta görs just nu manuellt.

Biblioteket använder ett paket som inte är inkluderat i php.
Om du har en annan version av php så bör du kolla upp rätt kommando för den.
* cURL
    * php 5: ``` sudo apt install php5-curl ```
    * php 7.0: ``` sudo apt install php7.0-curl ```

### Joomla

#### Installation
Se först till att ha scoutnets webbkoppling aktiverad och ha api-nycklarna.
1. Hämta rätt release från github eller bygg den själv. Den ska heta 'scoutorg.zip'.
2. Logga in som admin i joomla och gå till Extensions->Manage.
3. Välj Upload Package File och dra in zipfilen.
4. Konfigurera pluginen 'ScoutOrg Plugin' att använda rätt kår och api-nycklar.
5. Aktivera pluginen (Bör ej göras innan den är konfigurerad).

#### Användning (Utveckling utav moduler och komponenter)
När pluginen är aktiverad och initieras vid varje sidhämtning kommer den att skapa en global variabel som heter $scoutOrg som är av typen [Org\Lib\ScoutOrg](https://github.com/scouternasetjanster/ScoutOrg/blob/master/src/Org/Lib/ScoutOrg.php).
I dokumentationen som går att generera kan man hitta samtliga metoder för att hämta de olika objekten i datastrukturen.

## Att bygga projektet och generera dokumentation
Om man vill bygga en plugin eller generera dokumentationen för biblioteket krävs att kunna köra 'make' samt några program beroende på vad man bygger.

### Bygga projektet
För att skapa pluginen för joomla kan man köra ``` make ``` eller ``` make joomla ```.
Zipfilen kommer då att hamna i 'build/joomla/'.
Om projektet skulle expandera och börjar ge stöd för att bygga en plugin för t.ex. wordpress så kommer ``` make ``` att bygga för samtliga mål.

``` make joomla ``` kräver programmet zip: ``` sudo apt install zip ```

### Generera dokumentation
För att generera dokumentationen kör man ``` make doc ```. Detta kräver att man har php och phpdoc (i /bin).

1. php 7.0 +
2. [phpdoc](https://github.com/phpDocumentor/phpDocumentor2)